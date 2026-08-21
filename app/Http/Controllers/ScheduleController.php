<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Http\Controllers;

use App\Models\Schedule;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Generator;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Domain\ValueObject\DateTime as IcalDateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\MultiDay;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Eluceo\iCal\Presentation\Factory\EventFactory;

class ScheduleController extends Controller
{
    /**
     * Timezone used for all schedule times.
     */
    const string TIMEZONE = 'America/Detroit';

    /**
     * Gets the data for the schedule page
     *
     * @return View
     */
    public function index(): View
    {
        $upcoming = Schedule::with('location')
            ->upcoming()
            ->take(20)
            ->get()
            ->groupByDate('start', 'Y-m-d')
            ->slice(0, 4);

        $full = Schedule::with(['location', 'scheduled'])
            ->withCount(['album', 'updates', 'stats'])
            ->orderBy('start', 'asc')
            ->get();

        return view('schedule', compact('upcoming', 'full'));
    }

    /**
     * Output the iCal file for the schedule
     *
     * @param Request $request
     * @return mixed
     */
    public function subscribe(Request $request): mixed
    {
        $schedule = Schedule::with(['location', 'scheduled'])
            ->withCount(['album', 'updates', 'stats'])
            ->orderBy('start', 'asc')
            ->get();

        $events = [];

        foreach ($schedule as $item) {
            $summary = trans('schedule.iCalSummary', [
                'team' => trans('misc.'.$item->team),
                'type' => $item->type,
                'title' => $item->type === Schedule::TOURNAMENT
                    ? ' - ' . $item->scheduled->title
                    : 'vs ' . $item->scheduled->opponent,
            ]);

            // Stable UID so subscribers don't see the event "change" every refresh.
            $uid = new UniqueIdentifier('HudsonvilleWaterPolo.com/schedule/' . $item->id);

            $vEvent = new ScheduleEvent($uid)
                ->setSummary($summary)
                ->setCategories([$item->team, $item->type])
                ->setLocation(new Location(
                    $item->location->title . "\n" . $item->location->full_address,
                    $item->location->title
                ));

            if ($item->type === Schedule::TOURNAMENT) {
                // Tournaments don't have start times. MultiDay's $lastDay is
                // inclusive, so - unlike the old library - we don't need to
                // add an extra day to make it display through the end date.
                $vEvent->setOccurrence(new MultiDay(
                    new Date($this->toLocalDateTime($item->start)),
                    new Date($this->toLocalDateTime($item->end))
                ));
            } else {
                // Emitted as absolute UTC instants (rather than TZID-tagged
                // local time against a VTIMEZONE) because Google Calendar's
                // "From URL" subscription importer doesn't reliably resolve
                // custom VTIMEZONE/TZID; a "Z" timestamp needs no timezone
                // lookup on the client's part and displays correctly everywhere.
                $vEvent->setOccurrence(new TimeSpan(
                    new IcalDateTime($this->toUtcDateTime($item->start), true),
                    new IcalDateTime($this->toUtcDateTime($item->end), true)
                ));
            }

            // descriptions
            $desc = [];

            if (strlen($item->scheduled->result)) {
                $desc[] = trans('vcal.result') . ' ' . $item->scheduled->result;
            }
            if (isset($item->score_us)) {
                $desc[] = trans('vcal.score') . ' ' . $item->score_us . ' - ' . $item->score_them;
            }
            if ($item->type === Schedule::GAME) {
                if ($item->stats_count) {
                    $desc[] = trans('vcal.stats') . ' ' . route('game.stats', ['game' => $item->scheduled->id]);
                }
                if ($item->album_count) {
                    $desc[] = trans('vcal.photos') . ' ' . route('game.photos', ['game' => $item->scheduled->id]);
                }
                if ($item->updates_count) {
                    $desc[] = trans('vcal.recap') . ' ' . route('game.recap', ['game' => $item->scheduled->id]);
                }
            }

            if (count($desc)) {
                $vEvent->setDescription(implode("\n", $desc));
                $vEvent->setDescriptionHtml('<p>' . implode('<br>', $desc));
            }

            $events[] = $vEvent;
        }

        $calendar = new ScheduleCalendar($events)
            ->setCalendarName(trans('vcal.name'))
            ->setCalendarDescription(trans('vcal.description'));

        $componentFactory = new ScheduleCalendarFactory(new ScheduleEventFactory());
        $data = (string) $componentFactory->createCalendar($calendar);

        if ($request->has('text')) {
            return response($data)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } else {
            return response($data)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="ical.ics"');
        }
    }

    /**
     * Convert a Carbon/DateTime instance to an immutable DateTime in the
     * schedule's timezone, since the presentation layer needs the timezone
     * on the value itself to match it up with the calendar's VTIMEZONE.
     *
     * @param  DateTimeInterface  $date
     * @return DateTimeImmutable
     */
    protected function toLocalDateTime(DateTimeInterface $date): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($date)
            ->setTimezone(new DateTimeZone(self::TIMEZONE));
    }

    /**
     * Convert a Carbon/DateTime instance to an immutable UTC DateTime, so
     * timed events render as absolute "Z" instants instead of TZID-tagged
     * local times.
     *
     * @param  DateTimeInterface  $date
     * @return DateTimeImmutable
     */
    protected function toUtcDateTime(DateTimeInterface $date): DateTimeImmutable
    {
        return $this->toLocalDateTime($date)
            ->setTimezone(new DateTimeZone('UTC'));
    }
}

/**
 * Adds the properties eluceo/ical's core Event entity doesn't (yet) expose
 * a first-class setter for: the HTML alternative description (X-ALT-DESC).
 * See https://ical.poerschke.nrw/docs/custom-properties for the pattern.
 */
class ScheduleEvent extends Event
{
    protected ?string $descriptionHtml = null;

    public function setDescriptionHtml(string $descriptionHtml): static
    {
        $this->descriptionHtml = $descriptionHtml;

        return $this;
    }

    public function getDescriptionHtml(): ?string
    {
        return $this->descriptionHtml;
    }
}

/**
 * Adds calendar-level X-WR-CALNAME / X-WR-CALDESC properties.
 */
class ScheduleCalendar extends Calendar
{
    protected ?string $calendarName = null;
    protected ?string $calendarDescription = null;

    public function setCalendarName(string $name): static
    {
        $this->calendarName = $name;

        return $this;
    }

    public function getCalendarName(): ?string
    {
        return $this->calendarName;
    }

    public function setCalendarDescription(string $description): static
    {
        $this->calendarDescription = $description;

        return $this;
    }

    public function getCalendarDescription(): ?string
    {
        return $this->calendarDescription;
    }
}

class ScheduleEventFactory extends EventFactory
{
    public function createComponent(Event $event): Component
    {
        $component = parent::createComponent($event);

        if ($event instanceof ScheduleEvent && $event->getDescriptionHtml() !== null) {
            // NOTE: Apple/Google clients look for FMTTYPE=text/html on this
            // property to render it as HTML. Check the Property/Parameter
            // API for the installed eluceo/ical version (composer show
            // eluceo/ical) and add the parameter here if available, e.g.
            // (new Property('X-ALT-DESC', new TextValue($event->getDescriptionHtml())))
            //     ->withParameter(new Parameter('FMTTYPE', ['text/html']))
            $component = $component->withProperty(
                new Property('X-ALT-DESC', new TextValue($event->getDescriptionHtml()))
            );
        }

        return $component;
    }
}

class ScheduleCalendarFactory extends CalendarFactory
{
    protected function getProperties(Calendar $calendar): Generator
    {
        yield from parent::getProperties($calendar);

        if ($calendar instanceof ScheduleCalendar) {
            if ($calendar->getCalendarName() !== null) {
                yield new Property('X-WR-CALNAME', new TextValue($calendar->getCalendarName()));
            }

            if ($calendar->getCalendarDescription() !== null) {
                yield new Property('X-WR-CALDESC', new TextValue($calendar->getCalendarDescription()));
            }
        }
    }
}
