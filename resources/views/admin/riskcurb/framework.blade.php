@extends('layouts.main')
@section('title', __('RiskCurb'))
@push('css')
    <style type="text/css">
        .risk-border-tiny {
            border: 1px solid black;
            height: 80vh;
            overflow: auto;
        }

        .risk-border-huge {
            border: 2px solid black;
            height: 80vh;
            margin: 0px 10px;
        }

        .risk-item {
            padding: 0px 10px;
            cursor: pointer;
        }

        .risk-header {
            width: 100%;
            background: #ccc;
            padding: 10px 20px
        }

        .risk-menus {
            display: flex;
            flex-direction: column;
        }

        .risk-menus li {
            padding: 10px 0px;
            border-bottom: 2px solid #ccc;
            cursor: pointer;
        }

        .content {
            height: 400px;
            overflow: hidden scroll;
        }

        body {
            margin: 30px;
            font-family: sans-serif;
        }

        .progressWrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        #fontSizeWrapper {
            font-size: 16px;
        }

        #fontSize {
            width: 100px;
            font-size: 1em;
        }

        /* ————————————————————–
                      Tree core styles
                    */
        .tree {
            margin: 1em;
        }

        .tree input {
            position: absolute;
            clip: rect(0, 0, 0, 0);
        }

        .tree input~ul {
            display: none;
        }

        .tree input:checked~ul {
            display: block;
        }

        /* ————————————————————–
                      Tree rows
                    */
        .tree li {
            line-height: 1.2;
            position: relative;
            padding: 0 0 1em 1em;
        }

        .tree ul li {
            padding: 1em 0 0 1em;
        }

        .tree>li:last-child {
            padding-bottom: 0;
        }

        /* ————————————————————–
                      Tree labels
                    */
        .tree_label {
            position: relative;
            display: inline-block;
            background: #fff;
        }

        label.tree_label {
            cursor: pointer;
        }

        label.tree_label:hover {
            color: #666;
        }

        /* ————————————————————–
                      Tree expanded icon
                    */
        label.tree_label:before {
            background: #000;
            color: #fff;
            position: relative;
            z-index: 1;
            float: left;
            margin: 0 1em 0 -2em;
            width: 1em;
            height: 1em;
            border-radius: 1em;
            content: '+';
            text-align: center;
            line-height: .9em;
        }

        :checked~label.tree_label:before {
            content: '–';
        }

        /* ————————————————————–
                      Tree branches
                    */
        .tree li:before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -.5em;
            display: block;
            width: 0;
            border-left: 1px solid #777;
            content: "";
        }

        .tree_label:after {
            position: absolute;
            top: 0;
            left: -1.5em;
            display: block;
            height: 0.5em;
            width: 1em;
            border-bottom: 1px solid #777;
            border-left: 1px solid #777;
            border-radius: 0 0 0 .3em;
            content: '';
        }

        label.tree_label:after {
            border-bottom: 0;
        }

        :checked~label.tree_label:after {
            border-radius: 0 .3em 0 0;
            border-top: 1px solid #777;
            border-right: 1px solid #777;
            border-bottom: 0;
            border-left: 0;
            bottom: 0;
            top: 0.5em;
            height: auto;
        }

        .tree li:last-child:before {
            height: 1em;
            bottom: auto;
        }

        .tree>li:last-child:before {
            display: none;
        }

        .tree_custom {
            display: block;
            background: #eee;
            padding: 1em;
            border-radius: 0.3em;
        }
    </style>
@endpush

@section('content')
    <div class="row">


        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8 risk-border-huge card">
                    <div class="card-body">
                        <div style="display: flex;justify-content:space-around;" class="risk-header">
                            <div class="risk-item" data-item="progress">
                                Basic Progress
                            </div>
                            <div class="risk-item" data-item="context">
                                Context
                            </div>
                            <div class="risk-item" data-item="criteria">
                                Criteria
                            </div>
                            <div class="risk-item" data-item="scope">
                                Scope
                            </div>
                            <div class="risk-item" data-item="framework">
                                Framework
                            </div>
                        </div>

                        <div class="middle_content"
                            style={{ request()->is('*framework*') ? 'display:block;' : 'display:none;' }}>
                            <div id="progress_content" class="content">
                                <h3>Your Progress: </h3><span> This section shows your progress between RiskCurb and your
                                    organization. </span>
                                <p>We will generate risks based on your organization information you answered from our
                                    RiskCurb Bot.</p>

                                <div class="progressWrapper">
                                    {{-- <div id="fontSizeWrapper">
                                    <label for="fontSize">Font size</label>
                                    <input type="range" value="1" id="fontSize" step="0.5" min="0.5" max="5" />
                                  </div> --}}

                                    <ul class="tree">
                                        <li>
                                            <input type="checkbox" checked="checked" id="c1" />
                                            <label class="tree_label" for="c1">Basic Progress</label>
                                            <ul>
                                                <li>
                                                    {{-- <input type="checkbox" checked="checked" id="c2" /> --}}
                                                    <input type="checkbox" id="c2" />
                                                    <label for="c2" class="tree_label">Level 1</label>
                                                    <ul>
                                                        <li><span class="tree_label">Organization:
                                                                {{ Utility::check_null($data ? $data->organization : '') }}</span>
                                                        </li>
                                                        <li><span class="tree_label">Organization Type:
                                                                {{ Utility::check_null($data ? $data->organization_type : '') }}</span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c3" />
                                                    <label for="c3" class="tree_label">Level 2: Location</label>
                                                    <ul>
                                                        <li><span class="tree_label">Country:
                                                                {{ Utility::check_null($data ? $data->country : '') }}</span>
                                                        </li>
                                                        <li><span class="tree_label">City:
                                                                {{ Utility::check_null($data ? $data->city : '') }}</span>
                                                        </li>
                                                        <li><span class="tree_label">State:
                                                                {{ Utility::check_null($data ? $data->state : '') }}</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" id="c4" />
                                                            <label for="c4" class="tree_label">Info..</label>
                                                            <ul>
                                                                <li><span class="tree_label">... </span></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li>
                                                    <input type="checkbox" id="c5" />
                                                    <label for="c5" class="tree_label">Level 3</label>
                                                    <ul>
                                                        <li><span class="tree_label">Assets:
                                                                {{ Utility::check_null($data ? $data->assets : '') }}</span>
                                                        </li>

                                                        <li>
                                                            <input type="checkbox" id="c6" />
                                                            <label for="c6" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span class="tree_label">....</span></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c7" />
                                                    <label for="c7" class="tree_label">Level 4</label>
                                                    <ul>
                                                        <li><span class="tree_label">Products: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c8" />
                                                            <label for="c8" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->products : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c9" />
                                                    <label for="c9" class="tree_label">Level 5</label>
                                                    <ul>
                                                        <li><span class="tree_label">Services: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c10" />
                                                            <label for="c10" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->services : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c11" />
                                                    <label for="c11" class="tree_label">Level 5</label>
                                                    <ul>
                                                        <li><span class="tree_label">Structure Type: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c12" />
                                                            <label for="c12" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->structure_type : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c13" />
                                                    <label for="c13" class="tree_label">Level 6</label>
                                                    <ul>
                                                        <li><span class="tree_label">Components: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c14" />
                                                            <label for="c14" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->components : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c15" />
                                                    <label for="c15" class="tree_label">Level 7</label>
                                                    <ul>
                                                        <li><span class="tree_label">Customer Types: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c16" />
                                                            <label for="c16" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->customer_types : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c17" />
                                                    <label for="c17" class="tree_label">Level 8</label>
                                                    <ul>
                                                        <li><span class="tree_label">Stakeholders: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c18" />
                                                            <label for="c18" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->stakeholders : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="c19" />
                                                    <label for="c19" class="tree_label">Level 9</label>
                                                    <ul>
                                                        <li><span class="tree_label">Workers: </span></li>
                                                        <li>
                                                            <input type="checkbox" id="c20" />
                                                            <label for="c20" class="tree_label">Info</label>
                                                            <ul>
                                                                <li><span
                                                                        class="tree_label">{{ Utility::check_null($data ? $data->workers : '') }}</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>


                                        {{-- <li>
                                      <input type="checkbox" id="c5" />
                                      <label class="tree_label" for="c5">Level 0</label>
                                      <ul>
                                        <li>
                                          <input type="checkbox" id="c6" />
                                          <label for="c6" class="tree_label">Level 1</label>
                                          <ul>
                                            <li><span class="tree_label">Level 2</span></li>
                                          </ul>
                                        </li>
                                        <li>
                                          <input type="checkbox" id="c7" />
                                          <label for="c7" class="tree_label">Level 1</label>
                                          <ul>
                                            <li><span class="tree_label">Level 2</span></li>
                                            <li>
                                              <input type="checkbox" id="c8" />
                                              <label for="c8" class="tree_label">Level 2</label>
                                              <ul>
                                                <li><span class="tree_label">Level 3</span></li>
                                              </ul>
                                            </li>
                                          </ul>
                                        </li>
                                      </ul>
                                    </li> --}}
                                    </ul>
                                </div>

                            </div>
                            <div id="context_content" style="display: none;" class="content">
                                <h1>Context Content</h1>
                            </div>
                            <div id="criteria_content" style="display: none;" class="content">
                                <h1>Criteria Content</h1>

                            </div>
                            <div id="scope_content" style="display: none;" class="content">
                                <h1>Scope Content</h1>
                            </div>
                            <div id="framework_content" style="display: none;" class="content">
                                <h1>Framework Content</h1>

                                <h3>{{ $content }}</h3>

                            </div>

                        </div>

                        <div class="middle_content"
                            style={{ request()->is('*documents*') ? 'display:block;' : 'display:none;' }}>
                            <h1>Documents Coming Soon!!!</h1>
                        </div>
                        <div class="middle_content"
                            style={{ request()->is('*insurance*') ? 'display:block;' : 'display:none;' }}>
                            <h1>Insurance Claims Coming Soon!!!</h1>
                        </div>
                        <div class="middle_content"
                            style={{ request()->is('*reports*') ? 'display:block;' : 'display:none;' }}>
                            <h1>Reports Coming Soon!!!</h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 card">
                    <div class="card-header">
                        Risk Bot
                    </div>
                    <div class="card-body" style="height: 50%;">
                        {!! Form::open([
                            'route' => 'framework.create',
                            'method' => 'Post',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class={{ 'form-group ' }} style={{ $step == '1' ? '' : 'display:none;' }}>
                            {{ Form::label('organization', __('Organization'), ['class' => 'form-label']) }}

                            {!! Form::text('organization', Utility::check_null($data ? $data->organization : ''), [
                                'placeholder' => __('Enter your organization name'),
                                'class' => 'form-control',
                                '',
                            ]) !!}

                        </div>

                        <div class="form-group" style={{ $step == '2' ? '' : 'display:none;' }}>

                            {{ Form::label('organization_type', __('Organization Type'), ['class' => 'form-label']) }}

                            {!! Form::text('organization_type', Utility::check_null($data ? $data->organization_type : ''), [
                                'placeholder' => __('Enter your organization type'),
                                'class' => 'form-control',
                                '',
                            ]) !!}

                        </div>

                        <div style={{ $step == '3' ? '' : 'display:none;' }}>
                            <div class="form-group">
                                {{ Form::label('city', __('city'), ['class' => 'form-label']) }}

                                {!! Form::text('city', Utility::check_null($data ? $data->city : ''), [
                                    'placeholder' => __('Enter your city'),
                                    'class' => 'form-control',
                                    '',
                                ]) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}

                                {!! Form::text(
                                    'state',
                                    Utility::check_null($data ? $data->state : ''),

                                    ['placeholder' => __('Enter your state'), 'class' => 'form-control', ''],
                                ) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}

                                {!! Form::text(
                                    'country',
                                    Utility::check_null($data ? $data->country : ''),

                                    ['placeholder' => __('Enter your Country'), 'class' => 'form-control', ''],
                                ) !!}

                            </div>

                        </div>

                        <div class="form-group" style={{ $step == '4' ? '' : 'display:none;' }}>
                            {{ Form::label('assets', __('Assets'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'assets',
                                Utility::check_null($data ? $data->assets : ''),

                                ['placeholder' => __('Enter your organization assets'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '5' ? '' : 'display:none;' }}>
                            {{ Form::label('products', __('Products'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'products',
                                Utility::check_null($data ? $data->products : ''),

                                ['placeholder' => __('Enter your organization products'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '6' ? '' : 'display:none;' }}>
                            {{ Form::label('services', __('Services'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'services',
                                Utility::check_null($data ? $data->services : ''),

                                ['placeholder' => __('Enter your services'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '7' ? '' : 'display:none;' }}>
                            {{ Form::label('structure_type', __('Structure type'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'structure_type',
                                Utility::check_null($data ? $data->structure_type : ''),

                                ['placeholder' => __('Enter your structure type'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '8' ? '' : 'display:none;' }}>
                            {{ Form::label('components', __('Components'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'components',
                                Utility::check_null($data ? $data->components : ''),

                                ['placeholder' => __('Components'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '9' ? '' : 'display:none;' }}>
                            {{ Form::label('customer_types', __('List Customer types'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'customer_types',
                                Utility::check_null($data ? $data->customer_types : ''),

                                ['placeholder' => __('Enter your customer types'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class={{ 'form-group' }} style={{ $step == '10' ? '' : 'display:none;' }}>
                            {{ Form::label('stakeholders', __('Stakeholders'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'stakeholders',
                                Utility::check_null($data ? $data->stakeholders : ''),

                                ['placeholder' => __('Enter your stakeholders'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '11' ? '' : 'display:none;' }}>
                            {{ Form::label('workers', __('Workers'), ['class' => 'form-label']) }}

                            {!! Form::textarea(
                                'workers',
                                Utility::check_null($data ? $data->workers : '0'),

                                ['placeholder' => __('Enter your workers'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                    </div>
                    <div class="card-footer">

                        <div class="form-group">
                            <div class="float-end">
                                <a href="{{ route('riskcurbKeysCancel') }}"
                                    class="btn btn-secondary">{{ __('Start') }}</a>
                                @if ($step >= '11')
                                    {{ Form::button(__('Finish'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                @else
                                    {{ Form::button(__('send'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
@push('javascript')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/marked" defer></script>
    <script>
        const items = document.querySelectorAll('.risk-item');
        let count = 0;

        items.forEach((item) => {
            item.onclick = (e) => {
                document.querySelectorAll('.content').forEach((content) => {
                    var data_item = item.getAttribute('data-item');
                    content.style.display = "none";
                    document.querySelector(`#${data_item}_content`).style.display = "block";
                    if (data_item !== "progress") {
                        $(`#${data_item}_content`).html("Generating content please wait......");

                        $.ajax({
                            url: "{{ route('SectionGenerateData') }}",
                            type: 'POST',
                            data: {
                                data: {
                                    section: data_item
                                },
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                let response = JSON.parse(data);

                                displayDataOneByOne(`#${data_item}_content`, 150, response
                                    .content, count);
                            },
                            error: function(data) {
                                $(`#${data_item}_content`).html(
                                    'Oops Something went wrong click again on the section item!!!'
                                );
                            }
                        });
                    }
                });
            }
        });
        const displayDataOneByOne = (node, dataInterval, data, count) => {
            let responseLength = data?.length;
            const responseInterval = setInterval(() => {
                count += 1;
                if (count <= responseLength) {
                    var newHtml = data?.toString()
                        .substr(0, count);
                    $(node).html(newHtml);
                }
            }, dataInterval);

            if (count >= responseLength) {
                window.clearInterval(responseInterval);
                count = 0;
            }
        }

        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        function setFontSize(el) {
            var fontSize = el.val();

            if (isNumber(fontSize) && fontSize >= 0.5) {
                $('body').css({
                    fontSize: fontSize + 'em'
                });
            } else if (fontSize) {
                el.val('1');
                $('body').css({
                    fontSize: '1em'
                });
            }
        }

        $(function() {

            $('#fontSize')
                .bind('change', function() {
                    setFontSize($(this));
                })
                .bind('keyup', function(e) {
                    if (e.keyCode == 27) {
                        $(this).val('1');
                        $('body').css({
                            fontSize: '1em'
                        });
                    } else {
                        setFontSize($(this));
                    }
                });

            $(window)
                .bind('keyup', function(e) {
                    if (e.keyCode == 27) {
                        $('#fontSize').val('1');
                        $('body').css({
                            fontSize: '1em'
                        });
                    }
                });

        });
    </script>
@endpush
