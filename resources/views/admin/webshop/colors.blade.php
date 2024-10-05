@extends('layouts/layoutMaster')

@section('title', 'Colors & Logo')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection


@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
@endsection

@section('page-script')
    <script>
        let colors = @json($data);
        const primaryColor = document.querySelector('#primary_color_picker');
        if (primaryColor) {
            var primaryColorpickr = pickr.create({
                el: primaryColor,
                theme: 'classic',
                default: colors.primary_color,
                swatches: [],
                components: {
                    // Main components
                    preview: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        rgba: true,
                        hex: true,
                        input: true,
                        save: true
                    }
                }
            }).on('init', instance => {
                document.querySelector('input[name="primary_color"]').value = colors.primary_color;
            }).on('save', (color, instance) => {
                var hexcolor = '#' + color.toHEXA().join('')
                document.querySelector('input[name="primary_color"]').value = hexcolor;
                instance.hide()
            });
        }

        const secondaryColor = document.querySelector('#secondary_color_picker');
        if (secondaryColor) {
            var secondaryColorpickr = pickr.create({
                el: secondaryColor,
                theme: 'classic',
                default: colors.secondary_color,
                swatches: [],
                components: {
                    // Main components
                    preview: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        rgba: true,
                        hex: true,
                        input: true,
                        save: true
                    }
                }
            }).on('init', instance => {
                document.querySelector('input[name="secondary_color"]').value = colors.secondary_color;
            }).on('save', (color, instance) => {
                var hexcolor = '#' + color.toHEXA().join('')
                document.querySelector('input[name="secondary_color"]').value = hexcolor;
                instance.hide()
            });
        }

        // intit checkbox 
        var font = colors.font
        fontElement = document.querySelector('input[name="font"][value="'+font+'"]')
        fontElement.checked = true
        fontElement.parentElement.parentElement.classList.add("checked")

        
    </script>

@endsection

@section('page-style')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
    @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap');

    :root {
        --font-family-Inter: "Inter", sans-serif;
        --font-family-Roboto: "Roboto", sans-serif;
        --font-family-Montserrat: "Montserrat", sans-serif;
        --font-family-Merriweather: "Merriweather", sans-serif;
    }
</style>
@endsection

@section('content')

    <!-- Users List Table -->
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Webshop /</span> Colors & Logo
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.webshop.colors.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($data['logo'] != '')
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $data['logo']) }}" alt="" width="700"
                            class="d-block mx-auto" style="background-color: {{ $data['primary_color'] }}; max-height:400px; object-fit:contain;padding: 15px;">
                    </div>
                @endif
                <div class="mb-4">
                    <label for="logo" class="form-label">Image</label>
                    <input class="form-control" type="file" id="logo" name="logo">
                    <span>Best size is width : 84px, height : 40px</span>
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <div class="">
                            <label for="primary_color" class="form-label">Primary color</label>
                            <div id="primary_color_picker"></div>
                            <input type="hidden" name="primary_color" id="primary_color">
                        </div>
                    </div>
                    <div class="col">
                        <div class="">
                            <label for="logo" class="form-label">Secondary color</label>
                            <div id="secondary_color_picker"></div>
                            <input type="hidden" name="secondary_color" id="secondary_color">
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <label for="logo" class="form-label">Fonts</label>
                    <div class="row">
                        <div class="col-md mb-md-0 mb-5">
                            <div class="form-check custom-option custom-option-basic h-100" style="font-family: var(--font-family-Inter);">
                                <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                    <input class="form-check-input" type="radio" value="Inter"
                                        id="customRadioTemp1" name="font">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Inter</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md">
                            <div class="form-check custom-option custom-option-basic h-100" style="font-family: var(--font-family-Roboto);">
                                <label class="form-check-label custom-option-content" for="customRadioTemp2">
                                    <input class="form-check-input" type="radio" value="Roboto"
                                        id="customRadioTemp2" name="font">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Roboto</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-check custom-option custom-option-basic h-100" style="font-family: var(--font-family-Montserrat);">
                                <label class="form-check-label custom-option-content" for="customRadioTemp3">
                                    <input class="form-check-input" type="radio" value="Montserrat"
                                        id="customRadioTemp3" name="font" >
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Montserrat</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-check custom-option custom-option-basic h-100" style="font-family: var(--font-family-Merriweather);">
                                <label class="form-check-label custom-option-content" for="customRadioTemp4">
                                    <input class="form-check-input" type="radio" value="Merriweather"
                                        id="customRadioTemp4" name="font">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Merriweather</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>

@endsection
