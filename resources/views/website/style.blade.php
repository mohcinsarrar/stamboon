<style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap");
    @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap');

    /*===========================
  COMMON css
===========================*/
    :root {
        --font-family: "{{ $data['colors']['font'] }}", sans-serif;
        --primary: {{ $data['colors']['primary_color'] }};
        --primary-dark: {{ $data['colors']['secondary_color'] }};
        --primary-light: #e2f1ff;
        --accent: #00d4d7;
        --accent-dark: #00bac1;
        --accent-light: #dff9f8;
        --success: #13d527;
        --success-dark: #00ae11;
        --success-light: #eafbe7;
        --secondary: #8f15d5;
        --secondary-dark: #6013c7;
        --secondary-light: #f4e5fa;
        --info: #15b2d5;
        --info-dark: #0f8ca8;
        --info-light: #e0f5fa;
        --caution: #dbbb25;
        --caution-dark: #d58f15;
        --caution-light: #fbf9e4;
        --error: #e6185e;
        --error-dark: #bf1257;
        --error-light: #fce4eb;
        --black: #1d1d1d;
        --dark-1: #2d2d2d;
        --dark-2: #4d4d4d;
        --dark-3: #6d6d6d;
        --gray-1: #8d8d8d;
        --gray-2: #adadad;
        --gray-3: #cdcdcd;
        --gray-4: #e0e0e0;
        --light-1: #efefef;
        --light-2: #f5f5f5;
        --light-3: #fafafa;
        --white: #ffffff;
        --gradient-1: linear-gradient(180deg, #155bd5 0%, #1c3ab6 100%);
        --gradient-2: linear-gradient(180deg, #155bd5 13.02%, #00d4d7 85.42%);
        --gradient-3: linear-gradient(180deg, #155bd5 0%, #8f15d5 100%);
        --gradient-4: linear-gradient(180deg, #155bd5 0%, #13d527 100%);
        --gradient-5: linear-gradient(180deg, #155bd5 0%, #15bbd5 100%);
        --gradient-6: linear-gradient(180deg, #155bd5 0%, #dbbb25 100%);
        --gradient-7: linear-gradient(180deg, #155bd5 0%, #e6185e 100%);
        --gradient-8: linear-gradient(180deg, #1c3ab6 0%, #00bac1 100%);
        --gradient-9: linear-gradient(180deg, #00d4d7 13.02%, #155bd5 85.42%);
        --shadow-1: 0px 0px 1px rgba(40, 41, 61, 0.08),
            0px 0.5px 2px rgba(96, 97, 112, 0.16);
        --shadow-2: 0px 0px 1px rgba(40, 41, 61, 0.04),
            0px 2px 4px rgba(96, 97, 112, 0.16);
        --shadow-3: 0px 0px 2px rgba(40, 41, 61, 0.04),
            0px 4px 8px rgba(96, 97, 112, 0.16);
        --shadow-4: 0px 2px 4px rgba(40, 41, 61, 0.04),
            0px 8px 16px rgba(96, 97, 112, 0.16);
        --shadow-5: 0px 2px 8px rgba(40, 41, 61, 0.04),
            0px 16px 24px rgba(96, 97, 112, 0.16);
        --shadow-6: 0px 2px 8px rgba(40, 41, 61, 0.08),
            0px 20px 32px rgba(96, 97, 112, 0.24);
    }

    body {
        font-family: var(--font-family);
        color: var(--black);
        font-size: 16px;
    }

    @media (max-width: 991px) {
        body {
            font-size: 14px;
        }
    }

    img {
        max-width: 100%;
    }

    a {
        display: inline-block;
    }

    a,
    button,
    a:hover,
    a:focus,
    input:focus,
    textarea:focus,
    button:focus {
        text-decoration: none;
        outline: none;
    }

    ul,
    ol {
        margin: 0px;
        padding: 0px;
        list-style-type: none;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 600;
        color: var(--black);
        margin: 0px;
    }

    h1,
    .h1 {
        font-size: 2.75em;
        line-height: 1.25;
    }

    h2,
    .h2 {
        font-size: 2.25em;
        line-height: 1.25;
    }

    h3,
    .h3 {
        font-size: 1.75em;
        line-height: 1.25;
    }

    h4,
    .h4 {
        font-size: 1.5em;
        line-height: 1.25;
    }

    h5,
    .h5 {
        font-size: 1.25em;
        line-height: 1.25;
    }

    h6,
    .h6 {
        font-size: 0.875em;
        line-height: 1.25;
    }

    .display-1 {
        font-size: 5.5em;
        line-height: 1.25;
    }

    .display-2 {
        font-size: 4.75em;
        line-height: 1.25;
    }

    .display-3 {
        font-size: 4em;
        line-height: 1.25;
    }

    .display-4 {
        font-size: 3.25em;
        line-height: 1.25;
    }

    p {
        font-size: 1em;
        font-weight: 400;
        line-height: 1.5;
        color: var(--dark-3);
        margin: 0px;
    }

    .text-small {
        font-size: 0.875em;
        line-height: 1.5;
    }

    .text-lg {
        font-size: 1.15em;
        line-height: 1.5;
    }

    .bg_cover {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }

    @media (max-width: 767px) {
        .container {
            padding-left: 20px;
            padding-right: 20px;
        }
    }

    .btn {
        font-weight: bold;
        font-size: 16px;
        line-height: 20px;
        text-align: center;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 12px 24px;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .btn:hover {
        color: inherit;
    }

    .btn:focus {
        box-shadow: none;
        outline: none;
    }

    .btn.btn-lg {
        font-size: 1.15em;
        padding: 16px 24px;
    }

    .btn.btn-sm {
        padding: 8px 16px;
    }

    .btn.square {
        border-radius: 0px;
    }

    .btn.semi-rounded {
        border-radius: 12px;
    }

    .btn.rounded-full {
        border-radius: 50px;
    }

    .btn.icon-left span,
    .btn.icon-left i {
        margin-right: 8px;
    }

    .btn.icon-right span,
    .btn.icon-right i {
        margin-left: 8px;
    }

    .btn.icon-btn {
        width: 48px;
        height: 48px;
        padding: 0;
        line-height: 48px;
    }

    .btn.icon-btn.btn-lg {
        width: 56px;
        height: 56px;
        line-height: 56px;
    }

    .btn.icon-btn.btn-sm {
        width: 40px;
        height: 40px;
        line-height: 40px;
    }


    /* ===== Buttons Css ===== */
    .primary-btn {
        background: var(--primary);
        color: var(--white);
        box-shadow: var(--shadow-2);
    }

    .active.primary-btn,
    .primary-btn:hover,
    .primary-btn:focus {
        background: var(--primary-dark);
        color: var(--white);
        box-shadow: var(--shadow-4);
    }

    .deactive.primary-btn {
        background: var(--gray-4);
        color: var(--dark-3);
        pointer-events: none;
    }

    .primary-btn-outline {
        border-color: var(--primary);
        color: var(--primary);
    }

    .active.primary-btn-outline,
    .primary-btn-outline:hover,
    .primary-btn-outline:focus {
        background: var(--primary-dark);
        color: var(--white);
    }

    .deactive.primary-btn-outline {
        color: var(--dark-3);
        border-color: var(--gray-4);
        pointer-events: none;
    }


    /* One Click Scrool Top Button*/
    .scroll-top {
        width: 45px;
        height: 45px;
        line-height: 45px;
        background: var(--primary);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        font-size: 14px;
        color: #fff !important;
        border-radius: 0;
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all .3s ease-out 0s;
        transition: all .3s ease-out 0s;
        border-radius: 5px;
    }

    .scroll-top:hover {
        -webkit-box-shadow: 0 1rem 3rem rgba(35, 38, 45, 0.15) !important;
        box-shadow: 0 1rem 3rem rgba(35, 38, 45, 0.15) !important;
        -webkit-transform: translate3d(0, -5px, 0);
        transform: translate3d(0, -5px, 0);
        background-color: var(--dark-1);
    }

    /*===========================
  Section Title Five CSS
===========================*/
    .section-title-five {
        text-align: center;
        max-width: 550px;
        margin: auto;
        margin-bottom: 50px;
        position: relative;
        z-index: 5;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .section-title-five {
            margin-bottom: 45px;
        }
    }

    @media (max-width: 767px) {
        .section-title-five {
            margin-bottom: 35px;
        }
    }

    .section-title-five h6 {
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 30px;
        padding: 8px 30px;
    }

    .section-title-five h2 {
        margin-bottom: 15px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .section-title-five h2 {
            font-size: 2rem;
            line-height: 2.8rem;
        }
    }

    @media (max-width: 767px) {
        .section-title-five h2 {
            font-size: 1.5rem;
            line-height: 1.9rem;
        }
    }

    .section-title-five p {
        color: var(--dark-3);
    }


    /*===========================
  NAVBAR css
===========================*/
    .navbar-toggler:focus {
        box-shadow: none;
    }

    .mb-100 {
        margin-bottom: 100px;
    }

    /*===== NAVBAR NINE =====*/
    .navbar-area.navbar-nine {
        background: var(--primary);
        padding: 10px 0;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        z-index: 9;
    }

    .sticky {
        position: fixed !important;
        z-index: 99 !important;
        -webkit-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        top: 0;
        width: 100%;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine {
            padding: 10px 0;
        }
    }

    .navbar-area.navbar-nine .navbar-brand {
        margin: 0;
    }

    .navbar-area.navbar-nine .navbar {
        position: relative;
        padding: 0;
    }

    .navbar-area.navbar-nine .navbar .navbar-toggler .toggler-icon {
        width: 30px;
        height: 2px;
        background-color: var(--white);
        margin: 5px 0;
        display: block;
        position: relative;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .navbar-area.navbar-nine .navbar .navbar-toggler.active .toggler-icon:nth-of-type(1) {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
        top: 7px;
    }

    .navbar-area.navbar-nine .navbar .navbar-toggler.active .toggler-icon:nth-of-type(2) {
        opacity: 0;
    }

    .navbar-area.navbar-nine .navbar .navbar-toggler.active .toggler-icon:nth-of-type(3) {
        -webkit-transform: rotate(135deg);
        -moz-transform: rotate(135deg);
        -ms-transform: rotate(135deg);
        -o-transform: rotate(135deg);
        transform: rotate(135deg);
        top: -7px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-collapse {
            position: absolute;
            top: 116%;
            left: 0;
            width: 100%;
            background-color: var(--primary);
            z-index: 8;
            padding: 10px 16px;
        }
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px),
    only screen and (min-width: 1400px) {
        .navbar-area.navbar-nine .navbar .navbar-nav {
            margin-left: 80px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav {
            margin-right: 0;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item {
        position: relative;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a {
        display: flex;
        align-items: center;
        padding: 11px 16px;
        color: var(--white);
        text-transform: capitalize;
        position: relative;
        border-radius: 5px;
        font-weight: 500;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        margin: 14px 0;
        opacity: 0.7;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a:hover {
        opacity: 1;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a {
            padding: 10px 0;
            display: block;
            border: 0;
            margin: 0;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a.active {
        opacity: 1;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a i {
        font-size: 12px;
        font-weight: 700;
        padding-left: 7px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item a i {
            position: relative;
            top: -5px;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu {
        position: absolute;
        left: 0;
        top: 130%;
        width: 230px;
        background-color: var(--white);
        border-radius: 5px;
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        z-index: 99;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);
        padding: 10px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu {
            position: relative !important;
            width: 100% !important;
            left: 0 !important;
            top: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
            right: auto;
            -webkit-transform: translateX(0%);
            -moz-transform: translateX(0%);
            -ms-transform: translateX(0%);
            -o-transform: translateX(0%);
            transform: translateX(0%);
            -webkit-transition: all none ease-out 0s;
            -moz-transition: all none ease-out 0s;
            -ms-transition: all none ease-out 0s;
            -o-transition: all none ease-out 0s;
            transition: all none ease-out 0s;
            box-shadow: none;
            text-align: left;
            border-top: 0;
            height: 0;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu.collapse:not(.show) {
        height: auto;
        display: block;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu.collapse:not(.show) {
            height: 0;
            display: none;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu.show {
            height: auto;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li {
        position: relative;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-nav-toggler {
        color: var(--black);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 16px;
        position: relative;
        color: var(--dark-2);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        border-radius: 0;
        margin: 0 0;
        z-index: 5;
        opacity: 1;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li a i {
        font-weight: 700;
        font-size: 12px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li a i {
            display: none;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li a .sub-nav-toggler i {
        display: inline-block;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-menu {
        right: auto;
        left: 100%;
        top: 0;
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px),
    only screen and (min-width: 1400px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-menu {
            margin-left: 10px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-menu {
            padding-left: 30px;
        }
    }

    @media (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-menu {
            padding-left: 30px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li .sub-menu.show {
            visibility: visible;
            height: auto;
            position: relative;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover .sub-menu {
        opacity: 1;
        visibility: visible;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover .sub-nav-toggler {
        color: var(--white);
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover .sub-nav-toggler {
            color: var(--primary);
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover>a {
        color: var(--primary);
        padding-left: 22px;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover>a i {
        color: var(--primary);
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover>a {
            color: var(--primary);
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover>a::after {
        opacity: 1;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-menu li:hover>a::before {
        opacity: 1;
    }

    .navbar-area.navbar-nine .navbar .navbar-nav .nav-item:hover .sub-menu {
        opacity: 1;
        visibility: visible;
        top: 115%;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-nav .nav-item .sub-nav-toggler {
            display: inline-block;
            position: absolute;
            top: 0;
            right: 0;
            padding: 10px 14px;
            font-size: 16px;
            background: none;
            border: 0;
            color: var(--white);
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-btn {
        margin-top: 6px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .navbar-area.navbar-nine .navbar .navbar-btn {
            position: absolute;
            right: 70px;
            top: 7px;
        }
    }

    @media (max-width: 767px) {
        .navbar-area.navbar-nine .navbar .navbar-btn {
            position: absolute;
            right: 60px;
            top: 7px;
        }
    }

    .navbar-area.navbar-nine .navbar .navbar-btn .menu-bar {
        font-size: 22px;
        position: relative;
        overflow: hidden;
        color: var(--white);
        height: 40px;
        width: 40px;
        line-height: 40px;
        text-align: center;
        border: 1px solid rgba(238, 238, 238, 0.425);
        border-radius: 50%;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .navbar-area.navbar-nine .navbar .navbar-btn .menu-bar:hover {
        border-color: transparent;
        color: var(--primary);
        background-color: var(--white);
    }

    .navbar-area.navbar-nine .login-btn {
        background-color: transparent;
        color: white;
        text-transform: lowercase;
        border-color: white;
        font-family: "Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
        font-size: 15px;
        font-weight: 500;
    }

    .navbar-area.navbar-nine .login-btn:hover {
        background-color: white;
        color: var(--primary);
    }

    /*===== SIDEBAR ONE =====*/
    .sidebar-left {
        position: fixed;
        top: 0;
        right: 0;
        background-color: var(--white);
        height: 100%;
        width: 350px;
        padding-top: 80px;
        z-index: 999;
        -webkit-transform: translateX(100%);
        -moz-transform: translateX(100%);
        -ms-transform: translateX(100%);
        -o-transform: translateX(100%);
        transform: translateX(100%);
        transition: all 0.4s ease-in-out;
        text-align: left;
    }

    .sidebar-left.open {
        -webkit-transform: translateX(0);
        -moz-transform: translateX(0);
        -ms-transform: translateX(0);
        -o-transform: translateX(0);
        transform: translateX(0);
    }

    @media (max-width: 767px) {
        .sidebar-left {
            width: 250px;
        }
    }

    .sidebar-left .sidebar-close {
        position: absolute;
        top: 30px;
        right: 30px;
    }

    .sidebar-left .sidebar-close .close {
        font-size: 18px;
        color: var(--black);
        -webkit-transition: all 0.2s ease-out 0s;
        -moz-transition: all 0.2s ease-out 0s;
        -ms-transition: all 0.2s ease-out 0s;
        -o-transition: all 0.2s ease-out 0s;
        transition: all 0.2s ease-out 0s;
        cursor: pointer !important;
    }

    .sidebar-left .sidebar-close .close:hover {
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .sidebar-left .sidebar-content {
        padding: 0px 30px;
    }

    .sidebar-left .sidebar-content .sidebar-menu {
        margin-top: 30px;
    }

    .sidebar-left .sidebar-content .sidebar-menu .menu-title {
        font-size: 18px;
        font-weight: 600;
    }

    .sidebar-left .sidebar-content .sidebar-menu ul {
        margin-top: 15px;
    }

    .sidebar-left .sidebar-content .sidebar-menu ul li a {
        font-size: 16px;
        line-height: 24px;
        font-weight: 500;
        padding: 8px 0;
        color: var(--dark-3);
        text-transform: capitalize;
        position: relative;
        border-radius: 5px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        display: block;
    }

    .sidebar-left .sidebar-content .sidebar-menu ul li a:hover {
        color: var(--primary);
        padding-left: 5px;
    }

    .sidebar-left .sidebar-content .text {
        margin-top: 20px;
    }

    .sidebar-left .sidebar-content .sidebar-social {
        margin-top: 30px;
    }

    .sidebar-left .sidebar-content .sidebar-social .social-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .sidebar-left .sidebar-content .sidebar-social ul li {
        display: inline-block;
        margin-right: 5px;
    }

    .sidebar-left .sidebar-content .sidebar-social ul li:last-child {
        margin: 0;
    }

    .sidebar-left .sidebar-content .sidebar-social ul li a {
        height: 38px;
        width: 38px;
        line-height: 38px;
        text-align: center;
        border: 1px solid #eee;
        border-radius: 50%;
        font-size: 18px;
        color: #666;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .sidebar-left .sidebar-content .sidebar-social ul li a:hover {
        color: var(--white);
        background-color: var(--primary);
        border-color: transparent;
    }

    .overlay-left {
        position: fixed;
        background-color: rgba(0, 0, 0, 0.6);
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        z-index: 99;
    }

    .overlay-left.open {
        display: block;
    }


    /* ===== Buttons Css ===== */
    .header-eight .primary-btn {
        background: var(--primary);
        color: var(--white);
        box-shadow: var(--shadow-2);
    }

    .header-eight .active.primary-btn,
    .header-eight .primary-btn:hover,
    .header-eight .primary-btn:focus {
        background: var(--primary-dark);
        color: var(--white);
        box-shadow: var(--shadow-4);
    }

    .header-eight .deactive.primary-btn {
        background: var(--gray-4);
        color: var(--dark-3);
        pointer-events: none;
    }

    /*======================================
    header Area CSS
========================================*/
    .header-eight {
        position: relative;
        padding: 160px 0 100px 0;
        background: var(--primary);
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-eight {
            padding: 130px 0 80px 0;
        }
    }

    @media (max-width: 767px) {
        .header-eight {
            padding: 100px 0 60px 0;
        }
    }

    .header-eight .header-image img {
        width: 100%;
        border-radius: 8px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .header-eight .header-image {
            margin-top: 40px;
        }
    }

    .header-eight .header-content {
        border-radius: 0;
        position: relative;
        z-index: 1;
        text-align: left;
    }

    .header-eight .header-content h1 {
        font-weight: 700;
        color: var(--white);
        text-shadow: 0px 3px 8px #00000017;
        text-transform: capitalize;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-eight .header-content h1 {
            font-size: 35px;
            line-height: 45px;
        }
    }

    @media (max-width: 767px) {
        .header-eight .header-content h1 {
            font-size: 30px;
            line-height: 42px;
        }
    }

    .header-eight .header-content h1 span {
        display: block;
    }

    .header-eight .header-content p {
        margin-top: 30px;
        color: var(--white);
        opacity: 0.7;
    }

    .header-eight .button {
        margin-top: 40px;
    }

    .header-eight .primary-btn {
        margin-right: 12px;
        background-color: var(--white);
        color: var(--primary);
        border: 1px solid transparent;
    }

    .header-eight .primary-btn:hover {
        background-color: transparent;
        color: var(--white);
        border-color: var(--white);
    }

    .header-eight .video-button {
        display: inline-flex;
        align-items: center;
    }

    @media (max-width: 767px) {
        .header-eight .video-button {
            margin-top: 20px;
        }
    }

    .header-eight .video-button .text {
        display: inline-block;
        margin-left: 15px;
        color: var(--white);
        font-weight: 600;
    }

    .header-eight .video-button .icon-btn {
        background: var(--white);
        color: var(--primary);
    }

    /*===========================
  about-05 css
===========================*/
    .about-five {
        background-color: var(--light-3);
        padding-top: 120px;
        padding-bottom: 90px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .about-five {
            padding-top: 100px;
            padding-bottom: 70px;
        }
    }

    @media (max-width: 767px) {
        .about-five {
            padding-top: 80px;
            padding-bottom: 60px;
        }
    }

    .about-five-content {
        padding-left: 50px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .about-five-content {
            padding-left: 0;
        }
    }

    @media (max-width: 767px) {
        .about-five-content {
            padding-left: 0;
        }
    }

    .about-five-content .small-title {
        position: relative;
        padding-left: 30px;
    }

    .about-five-content .small-title::before {
        position: absolute;
        content: "";
        left: 0;
        top: 50%;
        background-color: var(--primary);
        height: 2px;
        width: 20px;
        margin-top: -1px;
    }

    .about-five-content .main-title {
        margin-top: 20px;
    }

    .about-five-content .about-five-tab {
        margin-top: 40px;
    }

    .about-five-content .about-five-tab nav {
        border: none;
        background-color: var(--light-1);
        padding: 15px;
        border-radius: 5px;
    }

    .about-five-content .about-five-tab nav .nav-tabs {
        border: none;
    }

    .about-five-content .about-five-tab nav button {
        border: none;
        color: var(--dark-1);
        font-weight: 600;
        padding: 0;
        margin-right: 20px;
        position: relative;
        background-color: var(--white);
        padding: 10px 18px;
        border-radius: 4px;
        text-transform: capitalize;
    }

    @media (max-width: 767px) {
        .about-five-content .about-five-tab nav button {
            margin: 0;
            margin-bottom: 10px;
            width: 100%;
        }

        .about-five-content .about-five-tab nav button:last-child {
            margin: 0;
        }
    }

    .about-five-content .about-five-tab nav button:hover {
        color: var(--primary);
    }

    .about-five-content .about-five-tab nav button.active {
        background-color: var(--primary);
        color: var(--white);
    }

    .about-five-content .about-five-tab nav button:last-child {
        margin-right: 0;
    }

    .about-five-content .about-five-tab .tab-content {
        border: none;
        padding-top: 30px;
    }

    .about-five-content .about-five-tab .tab-content p {
        margin-bottom: 20px;
    }

    .about-five-content .about-five-tab .tab-content p:last-child {
        margin: 0;
    }

    .about-image-five {
        padding-left: 60px;
        position: relative;
        z-index: 2;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .about-image-five {
            margin-bottom: 70px;
            padding-left: 30px;
        }
    }

    @media (max-width: 767px) {
        .about-image-five {
            margin-bottom: 60px;
            padding-left: 0;
        }
    }

    .about-image-five .shape {
        position: absolute;
        left: 30px;
        top: -30px;
        z-index: -1;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .about-image-five .shape {
            left: 0;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .about-image-five::before {
            right: -15px;
            bottom: -15px;
        }
    }

    @media (max-width: 767px) {
        .about-image-five::before {
            display: none;
        }
    }

    .about-image-five img {
        width: 100%;
        z-index: 2;
    }


    /*===========================
  services css
===========================*/
    .services-eight {
        padding: 100px 0;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .services-eight {
            padding: 80px 0 50px 0;
        }
    }

    @media (max-width: 767px) {
        .services-eight {
            padding: 60px 0 30px 0;
        }
    }

    .services-eight .single-services {
        padding: 40px 30px;
        border: 1px solid var(--light-1);
        border-radius: 10px;
        margin-bottom: 30px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .services-eight .single-services:hover {
        box-shadow: var(--shadow-4);
    }



    .services-eight .single-services:hover .service-icon {
        /*color: var(--white);*/
        border-color: transparent;
        /*background: var(--primary);*/
    }

    .services-eight .single-services:hover .service-icon::after {
        opacity: 1;
        visibility: visible;
    }

    .services-eight .single-services .service-icon {
        width: 78px;
        height: 78px;
        /*border-radius: 50%;*/
        margin-bottom: 25px;
        background: var(--white);
        /*border: 2px solid var(--primary);*/
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 40px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        position: relative;
    }

    .services-eight .single-services .service-icon::after {
        content: "";
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: absolute;
        opacity: 0;
        visibility: hidden;
        background: var(--primary);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        z-index: -1;
        border-radius: 50%;
        border: 1px solid transparent;
    }

    .services-eight .single-services .service-content h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        padding-left: 30px;

    }


    .services-eight .single-services .service-content h4::before {
        position: absolute;
        content: "";
        left: 0;
        top: 12%;
        background-color: var(--primary);
        height: 20px;
        width: 20px;
        margin-top: -1px;
    }

    .services-eight .single-services .service-content p {
        color: var(--dark-3);
    }



    /*===== VIDEO ONE =====*/
    .video-one {
        background-color: var(--light-3);
        padding: 100px 0;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .video-one {
            padding: 80px;
        }
    }

    @media (max-width: 767px) {
        .video-one {
            padding: 60px 0;
        }
    }

    .video-one .video-title h5 {
        font-weight: 600;
        color: var(--primary);
    }

    .video-one .video-title h2 {
        font-weight: 700;
        color: var(--black);
        margin-top: 10px;
    }

    .video-one .video-title .text-lg {
        margin-top: 24px;
        color: var(--dark-3);
    }

    .video-one .video-content {
        position: relative;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    .video-one .video-content img {
        border-radius: 8px;
    }

    .video-one .video-content a {
        width: 88px;
        height: 88px;
        line-height: 88px;
        text-align: center;
        border-radius: 50%;
        background-color: var(--primary);
        color: var(--white);
        font-size: 30px;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
        padding-left: 3px;
    }

    @media (max-width: 767px) {
        .video-one .video-content a {
            width: 68px;
            height: 68px;
            line-height: 68px;
            font-size: 20px;
        }
    }

    .video-one .video-content a:hover {
        background-color: var(--white);
        color: var(--primary);
    }


    /*===== PRICING THIRTEEN =====*/
    .pricing-fourteen {
        background-color: var(--light-3);
        padding: 100px 0;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .pricing-fourteen {
            padding: 80px;
        }
    }

    @media (max-width: 767px) {
        .pricing-fourteen {
            padding: 60px 0;
        }
    }

    .pricing-style-fourteen {
        border: 1px solid var(--light-1);
        border-radius: 10px;
        margin-top: 30px;
        transition: all 0.4s ease;
        padding: 50px 35px;
        text-align: center;
        z-index: 0;
    }

    .pricing-style-fourteen:hover {
        box-shadow: var(--shadow-4);
    }

    .pricing-style-fourteen.middle {
        box-shadow: var(--shadow-4);
        border-color: var(--primary);
    }

    .pricing-style-fourteen .purchase-btn a.current {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .pricing-style-fourteen.middle .title {
        border-color: var(--primary);
        background: var(--primary);
        color: var(--white);
    }

    .pricing-style-fourteen .title {
        font-weight: 500;
        margin-bottom: 25px;
        color: var(--primary);
        padding: 8px 20px;
        border: 2px solid var(--primary);
        display: inline-block;
        border-radius: 30px;
        font-size: 16px;
    }

    .pricing-style-fourteen .table-head p {
        color: var(--dark-3);
    }

    .pricing-style-fourteen .price {
        padding-top: 30px;
    }

    .pricing-style-fourteen .amount {
        font-weight: 600;
        display: inline-block;
        position: relative;
        padding-left: 15px;
        font-size: 55px;
    }

    .pricing-style-fourteen .currency {
        font-weight: 400;
        color: var(--dark-3);
        font-size: 20px;
        position: absolute;
        left: 0;
        top: 6px;
    }

    .pricing-style-fourteen .duration {
        display: inline-block;
        font-size: 18px;
        color: var(--dark-3);
        font-weight: 400;
        font-size: 20px;
    }

    .pricing-style-fourteen .light-rounded-buttons {
        margin: 0;
        margin-top: 30px;
        margin-bottom: 40px;
    }

    .pricing-style-fourteen .table-list li {
        position: relative;
        margin-bottom: 10px;
        color: var(--dark-3);
        text-align: left;
    }

    .pricing-style-fourteen .table-list li:last-child {
        margin: 0;
    }

    .pricing-style-fourteen .table-list li i {
        color: var(--primary);
        font-size: 16px;
        padding-right: 8px;
    }

    .pricing-style-fourteen .table-list li i.deactive {
        color: var(--dark-3);
    }




    /* ===== Buttons Css ===== */
    .call-action .inner-content .light-rounded-buttons .primary-btn-outline {
        border-color: var(--primary);
        color: var(--primary);
    }

    .call-action .inner-content .light-rounded-buttons .active.primary-btn-outline,
    .call-action .inner-content .light-rounded-buttons .primary-btn-outline:hover,
    .call-action .inner-content .light-rounded-buttons .primary-btn-outline:focus {
        background: var(--white);
        color: var(--primary);
        border-color: transparent;
    }

    .call-action .inner-content .light-rounded-buttons .deactive.primary-btn-outline {
        color: var(--dark-3);
        border-color: var(--gray-4);
        pointer-events: none;
    }

    /*===== call action four =====*/
    .call-action {
        z-index: 2;
        padding: 100px 0;
        background: linear-gradient(45deg, var(--primary), var(--primary-dark));
        position: relative;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .call-action {
            padding: 80px;
        }
    }

    @media (max-width: 767px) {
        .call-action {
            padding: 60px 0;
        }
    }

    .call-action:before {
        position: absolute;
        content: "";
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top;
        z-index: -1;
    }

    .call-action .inner-content {
        text-align: center;
    }

    .call-action .inner-content h2 {
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--white);
    }

    .call-action .inner-content p {
        color: var(--white);
    }

    .call-action .inner-content .light-rounded-buttons {
        margin-top: 45px;
        display: block;
    }

    .call-action .inner-content .light-rounded-buttons .primary-btn-outline {
        border-color: var(--white);
        color: var(--white);
    }



    /*===== latest-news-area =====*/
    .latest-news-area {
        background: var(--white);
        padding: 100px 0;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .latest-news-area {
            padding: 80px;
        }
    }

    @media (max-width: 767px) {
        .latest-news-area {
            padding: 60px 0;
        }
    }

    .latest-news-area .single-news {
        margin-top: 30px;
    }

    .latest-news-area .single-news .image {
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }

    .latest-news-area .single-news .image img {
        height: 100%;
        width: 100%;
        transition: all 0.4s ease;
    }

    .latest-news-area .single-news .image .meta-details {
        display: inline-block;
        padding: 6px 15px 6px 7px;
        border-radius: 30px;
        background-color: var(--primary);
        position: absolute;
        right: 20px;
        bottom: 20px;
    }

    .latest-news-area .single-news .image .meta-details img {
        height: 28px;
        width: 28px;
        border-radius: 50%;
        display: inline-block;
    }

    .latest-news-area .single-news .image .meta-details span {
        color: var(--white);
        display: inline-block;
        margin-left: 10px;
        font-size: 10px;
        font-weight: 500;
    }

    .latest-news-area .single-news .content-body .title {
        margin: 30px 0 20px 0;
    }

    .latest-news-area .single-news .content-body .title a {
        color: var(--black);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .latest-news-area .single-news .content-body .title a:hover {
        color: var(--primary);
    }

    .latest-news-area .single-news .content-body p {
        color: var(--dark-3);
    }

    .latest-news-area .single-news:hover .image .thumb {
        transform: scale(1.1) rotate(1deg);
    }


    /*======================================
    Brand CSS
========================================*/
    .brand-area {
        padding: 100px 0;
        background: var(--light-3);
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .brand-area {
            padding: 80px;
        }
    }

    @media (max-width: 767px) {
        .brand-area {
            padding: 60px 0;
        }
    }

    .brand-area .clients-logos {
        text-align: center;
        display: inline-block;
        margin-top: 20px;
    }

    .brand-area .clients-logos .single-image {
        display: inline-block;
        margin: 13px 10px;
        background-color: var(--white);
        line-height: 100px;
        padding: 8px 25px;
        border-radius: 8px;
        -webkit-transition: all 0.4s ease-out 0s;
        -moz-transition: all 0.4s ease-out 0s;
        -ms-transition: all 0.4s ease-out 0s;
        -o-transition: all 0.4s ease-out 0s;
        transition: all 0.4s ease-out 0s;
        border: 1px solid #eee;
    }

    .brand-area .clients-logos .single-image:hover {
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.096);
        -webkit-transform: translateY(-5px);
        -moz-transform: translateY(-5px);
        -ms-transform: translateY(-5px);
        -o-transform: translateY(-5px);
        transform: translateY(-5px);
    }

    .brand-area .clients-logos img {
        max-width: 170px;
        transition: all 0.4s ease-in-out;
        cursor: pointer;
    }

    /* ===== Buttons Css ===== */
    .contact-form-wrapper .contact-form .primary-btn {
        background: var(--primary);
        color: var(--white);
        box-shadow: var(--shadow-2);
    }

    .contact-form-wrapper .contact-form .active.primary-btn,
    .contact-form-wrapper .contact-form .primary-btn:hover,
    .contact-form-wrapper .contact-form .primary-btn:focus {
        background: var(--primary-dark);
        color: var(--white);
        box-shadow: var(--shadow-4);
    }

    .contact-form-wrapper .contact-form .deactive.primary-btn {
        background: var(--gray-4);
        color: var(--dark-3);
        pointer-events: none;
    }

    /*======================================
 FAQ
========================================*/
    .py-8 {
        padding-bottom: 4.5rem !important;
        padding-top: 4.5rem !important
    }

    @media(min-width:576px) {
        .py-sm-8 {
            padding-bottom: 4.5rem !important;
            padding-top: 4.5rem !important
        }
    }

    @media(min-width:768px) {
        .py-md-8 {
            padding-bottom: 4.5rem !important;
            padding-top: 4.5rem !important
        }
    }

    @media(min-width:992px) {
        .py-lg-8 {
            padding-bottom: 4.5rem !important;
            padding-top: 4.5rem !important
        }
    }

    @media(min-width:1200px) {
        .py-xl-8 {
            padding-bottom: 4.5rem !important;
            padding-top: 4.5rem !important
        }
    }

    @media(min-width:1400px) {
        .py-xxl-8 {
            padding-bottom: 4.5rem !important;
            padding-top: 4.5rem !important
        }
    }

    .bsb-btn-xl {
        --bs-btn-padding-y: 0.625rem;
        --bs-btn-padding-x: 1.25rem;
        --bs-btn-font-size: calc(1.26rem + 0.12vw);
        --bs-btn-border-radius: var(--bs-border-radius-lg)
    }

    @media(min-width:1200px) {
        .bsb-btn-xl {
            --bs-btn-font-size: 1.35rem
        }
    }

    .bsb-btn-2xl {
        --bs-btn-padding-y: 0.75rem;
        --bs-btn-padding-x: 1.5rem;
        --bs-btn-font-size: calc(1.27rem + 0.24vw);
        --bs-btn-border-radius: var(--bs-border-radius-lg)
    }

    @media(min-width:1200px) {
        .bsb-btn-2xl {
            --bs-btn-font-size: 1.45rem
        }
    }

    .bsb-btn-3xl {
        --bs-btn-padding-y: 0.875rem;
        --bs-btn-padding-x: 1.75rem;
        --bs-btn-font-size: calc(1.28rem + 0.36vw);
        --bs-btn-border-radius: var(--bs-border-radius-lg)
    }

    @media(min-width:1200px) {
        .bsb-btn-3xl {
            --bs-btn-font-size: 1.55rem
        }
    }

    .bsb-btn-4xl {
        --bs-btn-padding-y: 1rem;
        --bs-btn-padding-x: 2rem;
        --bs-btn-font-size: calc(1.29rem + 0.48vw);
        --bs-btn-border-radius: var(--bs-border-radius-lg)
    }

    @media(min-width:1200px) {
        .bsb-btn-4xl {
            --bs-btn-font-size: 1.65rem
        }
    }

    .bsb-btn-5xl {
        --bs-btn-padding-y: 1.125rem;
        --bs-btn-padding-x: 2.25rem;
        --bs-btn-font-size: calc(1.3rem + 0.6vw);
        --bs-btn-border-radius: var(--bs-border-radius-lg)
    }

    @media(min-width:1200px) {
        .bsb-btn-5xl {
            --bs-btn-font-size: 1.75rem
        }
    }

    .bsb-faq-2 .accordion {
        --bs-accordion-btn-icon: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2716%27 height=%2716%27 fill=%27%23212529%27 class=%27bi bi-plus%27%3E%3Cpath d=%27M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z%27/%3E%3C/svg%3E");
        --bs-accordion-btn-active-icon: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2716%27 height=%2716%27 fill=%27%23052c65%27 class=%27bi bi-dash%27%3E%3Cpath d=%27M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z%27/%3E%3C/svg%3E")
    }

    .bsb-faq-2 .accordion-button:not(.collapsed) {
        color: var(--primary) !important;
    }

    /*======================================
 login
========================================*/

    .login-dropdown .dropdown .btn {
        font-weight: bold;
        font-size: 13px;
        line-height: 20px;
        text-align: center;
        letter-spacing: 0.08em;
        text-transform: uppercase !important;
        color: white;
        padding: 12px 24px;
        border-radius: 4px;
        border: 1px solid transparent;
        background-color: transparent !important;
        border: 0 !important;
    }

    .login-dropdown .dropdown .btn:hover {
        text-decoration: underline;
    }

    .login-dropdown .dropdown .btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0) !important;
    }

    /*======================================
 Contact CSS
========================================*/
    .contact-section {
        position: relative;
        z-index: 3;
        padding-top: 100px;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .contact-section {
            padding-top: 80px;
        }
    }

    @media (max-width: 767px) {
        .contact-section {
            padding-top: 60px;
        }
    }

    .contact-section .contact-item-wrapper .contact-item {
        display: flex;
        border: 1px solid var(--gray-4);
        border-radius: 10px;
        background: var(--white);
        margin-bottom: 30px;
        padding: 20px 30px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .contact-section .contact-item-wrapper .contact-item:hover {
        box-shadow: var(--shadow-4);
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px) {
        .contact-section .contact-item-wrapper .contact-item {
            padding: 20px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .contact-section .contact-item-wrapper .contact-item {
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .contact-section .contact-item-wrapper .contact-item {
            flex-direction: column;
        }
    }

    .contact-section .contact-item-wrapper .contact-item .contact-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary);
        color: var(--white);
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .contact-section .contact-item-wrapper .contact-item .contact-content {
        margin-left: 25px;
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px) {
        .contact-section .contact-item-wrapper .contact-item .contact-content {
            margin-left: 20px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .contact-section .contact-item-wrapper .contact-item .contact-content {
            margin-left: 0px;
            margin-top: 20px;
        }
    }

    @media (max-width: 767px) {
        .contact-section .contact-item-wrapper .contact-item .contact-content {
            margin-left: 0px;
            margin-top: 20px;
        }
    }

    .contact-section .contact-item-wrapper .contact-item .contact-content h4 {
        font-size: 20px;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .contact-form-wrapper {
        padding: 50px 40px;
        background: var(--white);
        border: 1px solid var(--gray-4);
        margin-left: 0px;
        border-radius: 10px;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .contact-form-wrapper:hover {
        box-shadow: var(--shadow-4);
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px) {
        .contact-form-wrapper {
            margin-left: 30px;
        }
    }

    .contact-form-wrapper .section-title {
        margin-bottom: 30px;
    }

    .contact-form-wrapper .section-title span {
        font-size: 20px;
        color: var(--primary);
        font-weight: 700;
    }

    .contact-form-wrapper .section-title h2 {
        margin-bottom: 10px;
    }

    .contact-form-wrapper .section-title p {
        color: var(--dark-3);
    }

    .contact-form-wrapper .contact-form input,
    .contact-form-wrapper .contact-form textarea {
        padding: 15px 25px;
        border-radius: 30px;
        border: 1px solid var(--gray-4);
        margin-bottom: 25px;
        width: 100%;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    @media (max-width: 767px) {

        .contact-form-wrapper .contact-form input,
        .contact-form-wrapper .contact-form textarea {
            padding: 12px 25px;
        }
    }

    .contact-form-wrapper .contact-form input:focus,
    .contact-form-wrapper .contact-form textarea:focus {
        border-color: var(--primary);
    }

    .contact-form-wrapper .contact-form textarea {
        border-radius: 18px;
    }

    .map-style-9 {
        margin-top: -130px;
    }


    /* Footer eleven css */
    .footer-eleven {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: var(--primary);
        position: relative;
        margin-top: 50px;
        color: white !important;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .footer-eleven {
            padding-top: 30px;
            padding-bottom: 70px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .footer-eleven .footer-widget {
            margin-top: 40px;
        }
    }

    @media (max-width: 767px) {
        .footer-eleven .footer-widget {
            margin-top: 40px;
            text-align: center;
        }
    }

    .footer-eleven .footer-widget h5 {
        font-weight: 700;
        margin-bottom: 35px;
        color: var(--white);
    }

    @media only screen and (min-width: 768px) and (max-width: 991px),
    (max-width: 767px) {
        .footer-eleven .footer-widget h5 {
            margin-bottom: 25px;
        }
    }

    .footer-eleven .f-about {
        padding-right: 30px;
    }

    @media (max-width: 767px) {
        .footer-eleven .f-about {
            padding: 0;
        }
    }

    .footer-eleven .f-about p {
        color: var(--white);
        margin-top: 20px;
    }

    .footer-eleven .f-about .copyright-text {
        color: var(--dark-3);
        margin-top: 40px;
    }

    .footer-eleven .f-about .copyright-text span {
        display: block;
    }

    @media (max-width: 767px) {
        .footer-eleven .f-about .copyright-text {
            margin-top: 20px;
        }
    }

    .footer-eleven .f-about .copyright-text a {
        color: var(--primary);
    }

    .footer-eleven .f-about .copyright-text a:hover {
        color: var(--primary-dark);
    }

    .footer-eleven .f-link li {
        display: block;
        margin-bottom: 12px;
    }

    .footer-eleven .f-link li:last-child {
        margin: 0;
    }

    .footer-eleven .f-link li a {
        color: var(--dark-3);
        -webkit-transition: all 0.4s ease-out 0s;
        -moz-transition: all 0.4s ease-out 0s;
        -ms-transition: all 0.4s ease-out 0s;
        -o-transition: all 0.4s ease-out 0s;
        transition: all 0.4s ease-out 0s;
    }

    .footer-eleven .f-link li a:hover {
        color: var(--primary);
    }

    @media only screen and (min-width: 1200px) and (max-width: 1399px),
    only screen and (min-width: 1400px) {
        .footer-eleven .newsletter {
            padding-left: 80px;
        }
    }

    .footer-eleven .newsletter p {
        color: var(--dark-3);
    }

    .footer-eleven .newsletter-form {
        margin-top: 30px;
        position: relative;
    }

    .footer-eleven .newsletter-form input {
        height: 55px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid var(--gray-4);
        box-shadow: none;
        text-shadow: none;
        padding-left: 18px;
        padding-right: 65px;
        transition: all 0.4s ease;
    }

    .footer-eleven .newsletter-form input:focus {
        border-color: var(--primary);
    }

    .footer-eleven .newsletter-form .button {
        position: absolute;
        right: 7px;
        top: 50%;
        transform: translateY(-50%);
    }

    .footer-eleven .newsletter-form .sub-btn {
        height: 42px;
        width: 42px;
        border-radius: 6px;
        background-color: var(--primary);
        color: var(--white);
        text-align: center;
        line-height: 42px;
        border: none;
        box-shadow: none;
        text-shadow: none;
        font-size: 17px;
        transition: all 0.4s ease;
    }

    .footer-eleven .newsletter-form .sub-btn:hover {
        color: var(--white);
        background-color: var(--primary-dark);
    }


    .footer-eleven .sidebar-social .social-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .footer-eleven .sidebar-social ul li {
        display: inline-block;
        margin-right: 5px;
    }

    .footer-eleven .sidebar-social ul li:last-child {
        margin: 0;
    }

    .footer-eleven .sidebar-social ul li a {
        height: 38px;
        width: 38px;
        line-height: 38px;
        text-align: center;
        border: 1px solid transparent;
        border-radius: 50%;
        font-size: 18px;
        color: white;
        -webkit-transition: all 0.3s ease-out 0s;
        -moz-transition: all 0.3s ease-out 0s;
        -ms-transition: all 0.3s ease-out 0s;
        -o-transition: all 0.3s ease-out 0s;
        transition: all 0.3s ease-out 0s;
    }

    .footer-eleven .sidebar-social ul li a:hover {
        color: var(--white);
        background-color: var(--primary);
        border-color: #eee;
    }

    .pages a {
        color: white;
    }

    .pages a:hover {
        color: white;
        text-decoration: underline;
    }


    
</style>