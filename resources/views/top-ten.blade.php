@extends('layouts.app')

@section('title')
    @lang('top-ten.TopTen') -
@endsection

@section('content')

    <article class="page--topTen">
        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"></div>
            </div>
            <div class="container">
                <h1><span class="text--muted">@lang('top-ten.Top')</span> @lang('top-ten.Ten')</h1>
            </div>
        </header>

        @inject('topTen', 'App\Services\TopTenService')

        @foreach(App\Services\TopTenService::STATS_ORDER as $stat)
            <section class="page-section @if ($loop->index % 2) bg--smoke @endif">
                <div class="container">
                    <header class="divider--bottom text-align--center">
                        <h1>@mutedHeading(__('top-ten.'.$stat))</h1>
                    </header>

                    @foreach(App\Services\TopTenService::TYPES_ORDER as $type)
                        {{-- might want to add stats-table here too, but need to look into that a bit more --}}
                        <table class="table table--fancyCaption rankings table--striped">
                            <caption>@lang('top-ten.'.$type)</caption>

                            <tbody>
                            @foreach($topTen->getDataForStatAndType($stat, $type) as $row)
                                <tr>
                                    <th>{{ $row->rank }}</th>
                                    <td>{{ $row->player }}</td>
                                    @if($type === \App\Services\TopTenService::SEASON)<td>{{ $row->season }}</td>@endif
                                    <td>{{ $row->val }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </section>
        @endforeach
    </article>
@endsection