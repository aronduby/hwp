<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Eluceo\iCal\Component\Timezone;
use Eluceo\iCal\Component\TimezoneRule;
use Eluceo\iCal\Property\Event\RecurrenceRule;
use Illuminate\Http\Request;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;

use App\Http\Requests;

class ScheduleController extends Controller
{


    /**
     * Gets the data for the schedule page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
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
     * @return mixed
     */
    public function subscribe(Request $request)
    {
        $schedule = Schedule::with(['location', 'scheduled'])
            ->withCount(['album', 'updates', 'stats'])
            ->orderBy('start', 'asc')
            ->get();

        $tz = $this->getTimezone();

        $vCal = new Calendar('HudsonvilleWaterPolo.com');
        $vCal
            ->setName(trans('vcal.name'))
            ->setDescription(trans('vcal.description'))
            ->setCalId('HudsonvilleWaterPolo.com')
            ->setTimezone($tz);

        foreach ($schedule as $item) {
            $vEvent = new Event();
            $vEvent
                ->setDtStamp($item->start)
                ->setDtStart($item->start)
                ->setDtEnd($item->end)
                ->setUseTimezone(true)
                ->setSummary(trans('schedule.iCalSummary', [
                    'team' => trans('misc.'.$item->team),
                    'type' => $item->type,
                    'title' => $item->type === Schedule::TOURNAMENT
                        ? ' - ' . $item->scheduled->title
                        : 'vs ' . $item->scheduled->opponent
                ]))
                ->setCategories([$item->team, $item->type])
                ->setLocation($item->location->title . "\n" . $item->location->full_address, $item->location->title);

            if ($item->type === Schedule::TOURNAMENT) {
                // tournaments don't have start times
                $vEvent->setNoTime(true);

                // iCal end dates aren't inclusive, so to be able to get it to display on the end date add another day
                $interval = new \DateInterval('P1D');
                $vEvent->setDtEnd($item->end->add($interval));
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
                    $desc[] = trans('vcal.stats') . ' ' . route('game.stats', ['id' => $item->scheduled->id]);
                }
                if ($item->album_count) {
                    $desc[] = trans('vcal.photos') . ' ' . route('game.photos', ['id' => $item->scheduled->id]);
                }
                if ($item->updates_count) {
                    $desc[] = trans('vcal.recap') . ' ' . route('game.recap', ['id' => $item->scheduled->id]);
                }
            }

            if (count($desc)) {
                $vEvent->setDescription(implode("\n", $desc));
                $vEvent->setDescriptionHTML('<p>'.implode('<br>', $desc));
            }

            $vCal->addComponent($vEvent);
        }

        $data = $vCal->render();

        if ($request->has('text')) {
            return response($data)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } else {
            return response($data)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="ical.ics"');
        }
    }

    protected function getTimezone() {
        $tz  = 'America/Detroit';
        $dtz = new \DateTimeZone($tz);

        // Create timezone rule object for Standard Time
        $std = new TimezoneRule(TimezoneRule::TYPE_STANDARD);
        $std->setTzName('US-Eastern-STD');
        $std->setDtStart(new \DateTime('1967-10-29 2:00:00', $dtz));
        $std->setTzOffsetFrom('-0400');
        $std->setTzOffsetTo('-0500');
        $stdRecurrenceRule = new RecurrenceRule();
        $stdRecurrenceRule->setFreq(RecurrenceRule::FREQ_YEARLY);
        $stdRecurrenceRule->setByDay('-1SU');
        $stdRecurrenceRule->setByMonth(10);
        $std->setRecurrenceRule($stdRecurrenceRule);

        // Create timezone rule object for Daylight Saving Time
        $dst = new TimezoneRule(TimezoneRule::TYPE_DAYLIGHT);
        $dst->setTzName('US-Eastern-DST');
        $dst->setDtStart(new \DateTime('1987-04-05 02:00:00', $dtz));
        $dst->setTzOffsetFrom('-0500');
        $dst->setTzOffsetTo('-0400');
        $dstRecurrenceRule = new RecurrenceRule();
        $dstRecurrenceRule->setFreq(RecurrenceRule::FREQ_YEARLY);
        $dstRecurrenceRule->setByDay('-1SU');
        $dstRecurrenceRule->setByMonth(4);
        $dst->setRecurrenceRule($dstRecurrenceRule);

        // Create timezone definition and add rules
        $vtz = new Timezone($tz);
        $vtz->addComponent($std);
        $vtz->addComponent($dst);

        return $vtz;
    }
}
