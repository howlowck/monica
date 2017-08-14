@extends('layouts.skeleton')

@section('content')
  <div class="people-list">
    {{ csrf_field() }}

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
      <div class="{{ Auth::user()->getFluidLayout() }}">
        <div class="row">
          <div class="col-xs-12">
            <ul class="horizontal">
              <li>
                <a href="/dashboard">{{ trans('app.breadcrumb_dashboard') }}</a>
              </li>
              <li>
                {{ trans('app.breadcrumb_list_contacts') }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="main-content">

      @if ($contacts->count() == 0)

        <div class="blank-people-state">
          <div class="{{ Auth::user()->getFluidLayout() }}">
            <div class="row">
              <div class="col-xs-12">
                <h3>{{ trans('people.people_list_blank_title') }}</h3>
                <div class="cta-blank">
                  <a href="/people/add" class="btn btn-primary">{{ trans('people.people_list_blank_cta') }}</a>
                </div>
                <div class="illustration-blank">
                  <img src="/img/people/blank.svg">
                </div>
              </div>
            </div>
          </div>
        </div>

      @else

        <div class="{{ auth()->user()->getFluidLayout() }}">
          <div class="row">

            <div class="col-xs-12 col-md-9">

              @if (! is_null($tag))
              <p class="clear-filter">
                {!! trans('people.people_list_filter_tag', ['name' => $tag->name]) !!}
                <a href="/people">{{ trans('people.people_list_clear_filter') }}</a>
              </p>
              @endif

              <ul class="list">

                {{-- Sorting options --}}
                <li class="people-list-item sorting">
                  {{ trans('people.people_list_stats', ['count' => $contacts->count()]) }}

                  <div class="options">
                    <div class="options-dropdowns">
                      <a href="" class="dropdown-btn" data-toggle="dropdown" id="dropdownSort">{{ trans('people.people_list_sort') }}</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownSort">
                        <a class="dropdown-item {{ (auth()->user()->contacts_sort_order == 'firstnameAZ')?'selected':'' }}" href="/people?sort=firstnameAZ">
                          {{ trans('people.people_list_firstnameAZ') }}
                        </a>

                        <a class="dropdown-item {{ (auth()->user()->contacts_sort_order == 'firstnameZA')?'selected':'' }}" href="/people?sort=firstnameZA">
                          {{ trans('people.people_list_firstnameZA') }}
                        </a>

                        <a class="dropdown-item {{ (auth()->user()->contacts_sort_order == 'lastnameAZ')?'selected':'' }}" href="/people?sort=lastnameAZ">
                          {{ trans('people.people_list_lastnameAZ') }}
                        </a>

                        <a class="dropdown-item {{ (auth()->user()->contacts_sort_order == 'lastnameZA')?'selected':'' }}" href="/people?sort=lastnameZA">
                          {{ trans('people.people_list_lastnameZA') }}
                        </a>
                      </div>
                    </div>

                  </div>
                </li>

                @foreach($contacts as $contact)

                <li class="people-list-item">
                  <a href="{{ route('people.show', $contact) }}">
                    @if ($contact->has_avatar == 'true')
                      <img src="{{ $contact->getAvatarURL(110) }}" width="43">
                    @else
                      @if (! is_null($contact->gravatar_url))
                        <img src="{{ $contact->gravatar_url }}" width="43">
                      @else
                        @if (count($contact->getInitials()) == 1)
                        <div class="avatar one-letter" style="background-color: {{ $contact->getAvatarColor() }};">
                          {{ $contact->getInitials() }}
                        </div>
                        @else
                        <div class="avatar" style="background-color: {{ $contact->getAvatarColor() }};">
                          {{ $contact->getInitials() }}
                        </div>
                        @endif
                      @endif
                    @endif
                    <span class="people-list-item-name">
                      {{ $contact->getCompleteName(auth()->user()->name_order) }}
                    </span>

                    <span class="people-list-item-information">
                      {{ trans('people.people_list_last_updated') }} {{ \App\Helpers\DateHelper::getShortDate($contact->updated_at) }}
                    </span>
                  </a>
                </li>

                @endforeach
              </ul>
            </div>

            <div class="col-xs-12 col-md-3 sidebar">
              <a href="/people/add" class="btn btn-primary sidebar-cta">
                {{ trans('people.people_list_blank_cta') }}
              </a>

              <ul>
              @foreach (auth()->user()->account->tags as $tag)
                @if ($tag->contacts()->count() > 0)
                <li>
                  <span class="pretty-tag"><a href="/people?tags={{ $tag->name_slug }}">{{ $tag->name }}</a></span>
                  <span class="number-contacts-per-tag">{{ trans_choice('people.people_list_contacts_per_tags', $tag->contacts()->count(), ['count' => $tag->contacts()->count()]) }}</span>
                </li>
                @endif
              @endforeach
              </ul>
            </div>

          </div>
        </div>

      @endif

    </div>

  </div>
@endsection
