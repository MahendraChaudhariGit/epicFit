<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->

<head>
    <title>Epic Result</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    {{-- <meta charset="utf-8" /> --}}
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="public_url" content="{{ asset('') }}">
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="author" content="@yield('meta_author', '')">
    @yield('meta')
    <!-- end: META -->

    <!-- start: GOOGLE FONTS -->
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
        rel="stylesheet" type="text/css" />

    <!-- end: GOOGLE FONTS -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- start: MAIN CSS -->
    @yield('before-styles-end')
    {!! Html::style('result/plugins/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('result/plugins/fontawesome/css/font-awesome.min.css') !!}
    {!! Html::style('result/plugins/fonts/style.css') !!}
    {!! Html::style('result/plugins/themify-icons/themify-icons.min.css') !!}
    {!! Html::style('result/plugins/animate.css/animate.min.css', ['media' => 'screen']) !!}
    {!! Html::style('result/plugins/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']) !!}
    {!! Html::style('result/plugins/switchery/switchery.min.css', ['media' => 'screen']) !!}
    {!! Html::style('result/css/intlTelInput.css') !!}
    {!! Html::style('result/css/tooltipster.css') !!}
    {!! Html::style('result/plugins/jquery-ui/jquery-ui-1.10.1.custom.css') !!}
    {!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}
    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    {!! Html::style('result/css/styles-orange.css?v=' . time()) !!}
    {!! Html::style('result/plugins/clip-two/main-navigation.css') !!}
    {!! Html::style('result/css/plugins.css?v=' . time()) !!}
    {!! Html::style('result/plugins/themes/theme-orange.css') !!}
    {!! Html::style('result/css/custom-style.css?v=' . time()) !!}

    <!-- end: CLIP-TWO CSS -->

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    @yield('required-styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    {!! Html::script('result/js/jquery-3.2.1.min.js') !!}
    <!-- {!! Html::script('result/plugins/jquery/jquery.min.js') !!} -->
    {!! Html::script('result/plugins/jquery/jquery-ui.min.js') !!}

    {!! Html::script('result/js/jquery.tooltipster.min.js') !!}
    <!-- Start: NEW datetimepicker css -->
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
    <!-- End: NEW datetimepicker css -->
    {!! Html::style('result/css/custom.css') !!}
    {!! Html::style('css/fbphotobox.css') !!}
    <!-- Start: Angular script with vp-form -->
    @yield('angular-scripts-required')
    <!-- End: Angular script with vp-form -->

    @yield('header-scripts')


    <!-- End: NEW timepicker css -->

    {!! Html::script('js/fbphotobox.js') !!}
    <script>
        $(document).ready(function() {
             $(".fbphotobox img").fbPhotoBox({
                rightWidth: 360,
                leftBgColor: "black",
                rightBgColor: "white",
                footerBgColor: "black",
                overlayBgColor: "#222",
                containerClassName: 'fbphotobox',
                imageClassName: 'photo',          
                onImageShow: function(image) { 
                    $(".fbphotobox img").fbPhotoBox("addTags",
                        [{
                            x: 0.3,
                            y: 0.3,
                            w: 0.3,
                            h: 0.3
                        }]
                    );                
                    $.ajax({
                        url: public_url + 'social/post/preview/' + $(this).attr("data-id"),
                        type: 'GET',
                        async: false,
                        success: function(data) { 
                            $(".fbphotobox-image-content").html(data.html); 
                            //emoji
                            $(".fb-add-emoji").emojioneArea({
                                // inline: true,
                                pickerPosition: "bottom",
                                tonesStyle: "radio",
                                saveEmojisAs: 'image',
                                // hideSource: false
                                // standalone: true,
                                pickerPosition: "bottom",
                                events: {
                                    keyup: function(editor, event) {
                                        if (this.getText().trim() != '') {
                                            if (event.which == 13 && ($.trim(
                                                        editor.text()).length >
                                                    0 || $.trim(editor.html())
                                                    .length > 0)) {
                                                $(".fb-add-emoji").data(
                                                        "emojioneArea")
                                                    .hidePicker();
                                                // if(this.getText().trim() != ''){
                                                const form = event.currentTarget
                                                    .closest('.emoji-div');
                                                const field = form
                                                    .querySelector(
                                                        '.fb-add-emoji[name="emoji"]'
                                                        );
                                                let post_id = field
                                                    .getAttribute(
                                                        'data-emoji-id');
                                                // var comment = editor.html();
                                                var comment = field.emojioneArea
                                                    .getText().trim();
                                                submitComment(comment, post_id);
                                                event.preventDefault();
                                                event.stopPropagation();
                                                editor.focus();
                                            }
                                        } else {
                                            if (event.which == 13) {
                                                event.preventDefault();
                                                return false;
                                            }
                                        }
                                    },
                                    emojibtn_click: function(button, event) {
                                        $("#comment-submit-button-" +
                                                add_post_id)[0].emojioneArea
                                            .hidePicker();
                                        $("#comment-submit-button2-" +
                                                add_post_id)[0].emojioneArea
                                            .hidePicker();
                                    },
                                    focus: function(editor, event) {
                                        const form = event.currentTarget
                                            .closest('.emoji-div');
                                        const field = form.querySelector(
                                            '.fb-add-emoji[name="emoji"]');
                                        let post_id = field.getAttribute(
                                            'data-emoji-id');
                                        add_post_id = post_id;
                                    },

                                    blur: function(editor, event) {
                                        const form = event.currentTarget
                                            .closest('.emoji-div');
                                        const field = form.querySelector(
                                            '.fb-add-emoji[name="emoji"]');
                                        var html = field.emojioneArea.getText()
                                            .trim();
                                        var rx =
                                            /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
                                        var text = html.replace(rx, "$1");
                                        field.emojioneArea.setText(text);
                                    },

                                }
                            });
                            //emoji - end
                        }
                    });
                }
            });
        });
    </script>
</head>
<!-- end: HEAD -->

<body>
    {{-- loader --}}
    <div id="waitingShield" class="text-center">
        <div>
            <i class="fa fa-circle-o-notch"></i>
        </div>
    </div>
    {{-- loader --}}
    @php
        $mealsCategoryArr = \App\MpMealCategory::pluck('id', 'name')->toArray();
    @endphp
    <div id="calPopupHelper" class="hidden"></div>

    <div id="app" class="app-sidebar-closed a-s-c">
        <!-- end: HEADER -->

        <!-- side bar -->
        @include('Result.partials.includes.sidebar')

        <!-- start: APP CONTENT -->
        <div class="app-content">

            <!-- top nav bar -->
            @include('Result.partials.includes.navbar')

            @include('Result.partials.includes.topmenu')

            <!-- start: MAIN CONTETN -->
            <div class="main-content">
                <div class="wrap-content container" id="container">

                    @include('Result.partials.includes.page_title')

                    @include('Result.partials.messages')

                    @yield('content')

                </div>
            </div>
            <!-- end: MAIN CONTETN -->

        </div>
        <!-- end: APP CONTENT -->

        @include('Result.partials.includes.footer')
        @include('Result.partials.dailyLogModal',['catType' => $mealsCategoryArr])

    </div>
    <!-- start: MAIN JAVASCRIPTS -->
    <script type="text/javascript">
        var BASE_URL = "{{ url('/') }}";
        // var REQUEST_URL = "<?= Request::url() ?>";
        var CSRF = "{{ csrf_token() }}";
        // var WALL_ACTIVE = false;
    </script>
    {!! Html::script('result/plugins/bootstrap/js/tethr.js') !!}
    {!! Html::script('result/plugins/bootstrap/js/bootstrap.js') !!}

    {!! Html::script('result/plugins/modernizr/modernizr.js') !!}
    {!! Html::script('result/plugins/jquery-cookie/jquery.cookie.js') !!}
    {!! Html::script('result/plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!}
    {!! Html::script('result/plugins/switchery/switchery.min.js') !!}
    {!! Html::script('result/plugins/selectFx/classie.js') !!}
    {!! Html::script('result/plugins/selectFx/selectFx.js') !!}
    {!! Html::script('result/plugins/moment/moment.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
    <!-- Start:  NEW datetimepicker js -->
    {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
    <!-- End: NEW datetimepicker js -->
    {!! Html::script('result/js/webcam.js') !!}
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
    {!! Html::script('result/js/webrtc.js') !!}
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('result/js/daily-log.js?v=' . time()) !!}
    {!! Html::script('result/js/shopping-list.js?v=' . time()) !!}
    {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}
    <!-- end: MAIN JAVASCRIPTS -->
    <!-- Sidebar Js -->
    {{-- {!! Html::script('result/js/sidebar/common-bundle-script.js?v='.time()) !!} --}}
    {!! Html::script('result/js/sidebar/perfect-scrollbar.js?v=' . time()) !!}
    {!! Html::script('result/js/sidebar/script.js?v=' . time()) !!}
    {!! Html::script('result/js/sidebar/sidebar-script.js?v=' . time()) !!}
    {!! Html::script('result/js/sidebar/sidebar.large.scripts.js?v=' . time()) !!}

    <script>
        /**
         * 01.01 : Initial configuration for sending ajax
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    </script>

    <!-- start: CLIP-TWO JAVASCRIPTS -->
    {!! Html::script('result/js/main.js?v=' . time()) !!}
    <!-- start: CLIP-TWO JAVASCRIPTS -->

    <!-- start: JavaScript required for this page -->
    @yield('required-script')
    <!-- end: JavaScript required for this page -->

    <!-- Start: Javascript script for this page -->
    @yield('script')
    <!-- End: Javascript script for this page -->

    <script>
        jQuery(document).ready(function() {
            Main.init();
            @yield('script-handler-for-this-page')
        });
    </script>

    @yield('custom-script')
    @yield('invoice')
    <script type="text/javascript">
        var d = new Date();
        var n = d.getTime();
        $('script').each(function() {
            var attrSrc = $(this).attr("src");
            if (attrSrc != undefined && attrSrc != '') {
                var src = $.trim($(this).attr('src'));
                $(this).attr('src', src + '?v=' + n);
            }
        });
    </script> 
    <script type="text/javascript">
        $('document').ready(function() {

            $('#waitingShield').addClass('hidden');
        })


        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 300) {
                $(".custom-img").addClass("stickyimg");
            } else {
                $(".custom-img").removeClass("stickyimg");
            }

            if (scroll >= 180) {
                $(".stepsection").addClass("stickystep");
            } else {
                $(".stepsection").removeClass("stickystep");
            }
        });
    </script>
    <script>
        $('#NutritionalJournal select').selectpicker().change(function() {
            $(this).valid()
        });
        $('#customMealplanmodal select').selectpicker().change(function() {
            $(this).valid()
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.js"></script>
</body>

</html>
