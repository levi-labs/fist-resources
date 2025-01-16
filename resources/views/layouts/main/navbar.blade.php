<div class="position-relative iq-banner">
    <!--Nav Start-->
    <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar">
        <div class="container-fluid navbar-inner">
            <a href="{{ url('/dashboard') }}" class="navbar-brand">

                <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                                transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                                transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                                transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                                transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="logo-mini">
                        <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                                transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                                transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                                transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                                transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                        </svg>
                    </div>
                </div>
                <!--logo End-->




                <h4 class="logo-title">{{ config('app.name') }}</h4>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                    </svg>
                </i>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <span class="mt-2 navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @php
                    if (auth('web')->user()->role == 'supplier') {
                        $notification_count = \App\Models\Notification::where('status', 'unread')
                            ->where('user_id', auth('web')->user()->id)
                            ->count();
                        $nofications_m = \App\Models\Notification::where('status', 'unread')
                            ->where('user_id', auth('web')->user()->id)
                            ->get();
                    }
                    if (auth('web')->user()->role == 'procurement') {
                        $notification_count = \App\Models\Notification::where('status', 'unread')
                            ->where('user_role', 'procurement')
                            ->count();
                        $nofications_m = \App\Models\Notification::where('status', 'unread')
                            ->where('user_role', 'procurement')
                            ->get();
                    }
                    if (auth('web')->user()->role == 'logistic') {
                        $notification_count = \App\Models\Notification::where('status', 'unread')
                            ->where('user_role', 'logistic')
                            ->count();
                        $nofications_m = \App\Models\Notification::where('status', 'unread')
                            ->where('user_role', 'logistic')
                            ->get();
                    }
                    if (auth('web')->user()->role == 'admin') {
                        $notification_count = \App\Models\Notification::where('status', 'unread')->count();
                        $nofications_m = \App\Models\Notification::where('status', 'unread')->limit(5)->get();
                    }
                    // dd($nofications_m);
                @endphp
                <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                    <li class="nav-item dropdown">

                        <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown"
                            class="position-relative">
                            <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z"
                                    fill="currentColor"></path>
                                <path opacity="0.4"
                                    d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z"
                                    fill="currentColor"></path>
                            </svg>

                            <span
                                class="position-absolute top-10 start-95 translate-middle badge rounded-pill bg-danger">
                                {{ $notification_count > 5 ? '5+' : $notification_count }} <span
                                    class="visually-hidden">unread messages</span>
                            </span>
                        </a>
                        <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="notification-drop">
                            <div class="m-0 shadow-none card">
                                <div class="py-3 card-header d-flex justify-content-between bg-primary rounded-top">
                                    <div class="header-title">
                                        <h5 class="mb-0 text-white">All Notifications</h5>
                                    </div>
                                </div>
                                <style>
                                    .my-hover:hover {
                                        background-color: #5272ef !important;
                                        color: white !important;
                                    }

                                    .my-hover:hover .iq-text {
                                        color: white !important;
                                    }
                                </style>
                                <div class="p-0 card-body">

                                    @forelse ($nofications_m as $notif)
                                        @php
                                            $type = $notif->order_type; // Tambahkan titik koma setelah penugasan

                                            if ($type == 'purchase restock') {
                                                $link = route('restock.purchase.show', $notif->related_order_id);
                                            } elseif ($type == 'purchase proposed product') {
                                                $link = route('propose.purchase.show', $notif->related_order_id);
                                            } elseif ($notif->notification_type == 'request restock') {
                                                $link = route('restock.inventory.show', $notif->related_order_id);
                                            } elseif ($notif->notification_type == 'request propose') {
                                                $link = route('propose.inventory.show', $notif->related_order_id);
                                            } elseif ($notif->notification_type == 'shipment restock shipped') {
                                                $link = route('shipment.restockShipped');
                                            } elseif ($notif->notification_type == 'shipment restock delivered') {
                                                $link = route('shipment.restockDelivered');
                                            } elseif ($notif->notification_type == 'shipment propose shipped') {
                                                $link = route('shipment.proposeShipped');
                                            } elseif ($notif->notification_type == 'shipment propose delivered') {
                                                $link = route('shipment.proposeDelivered');
                                            }

                                        @endphp
                                        <a href="{{ $link ?? '#' }}" class="iq-sub-card my-hover">
                                            <div class="d-flex align-items-center">
                                                {{-- <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                            src="../assets/images/shapes/01.png" alt=""> --}}

                                                <div class="ms-3 w-100">
                                                    <h6 class="mb-0 text-start iq-text">{{ $notif->message }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-0">{{ $notif->notification_type }}</p>
                                                        <small
                                                            class="float-end font-size-12">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <a href="#" class="iq-sub-card my-hover">
                                            <div class="d-flex align-items-center">
                                                {{-- <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                        src="../assets/images/shapes/01.png" alt=""> --}}

                                                <div class="ms-3 w-100">
                                                    <h6 class="mb-0 text-start iq-text">No Notifications</h6>
                                                </div>
                                            </div>
                                        </a>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown custom-drop">
                        <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <div class="caption ms-3 d-none d-md-block ">
                                <h6 class="mb-0 caption-title">{{ Auth::user()->name }}</h6>
                                <p class="mb-0 caption-sub-title">{{ Auth::user()->role }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.changePassword') }}">Change
                                    Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('auth.logout') }} ">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav> <!-- Nav Header Component Start -->
    <div class="iq-navbar-header" style="height: 215px;">
        <div class="container-fluid iq-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flex-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h1>Hello {{ Auth('web')->user()->name }}</h1>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="iq-header-img">
            <img src="{{ asset('/assets/images/dashboard/top-header.png') }}" alt="header"
                class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
            <img src="{{ asset('/assets/images/dashboard/top-header1.png') }}" alt="header"
                class="theme-color-purple-img img-fluid w-100 h-100 animated-scaleX">
            <img src="{{ asset('/assets/images/dashboard/top-header2.png') }}" alt="header"
                class="theme-color-blue-img img-fluid w-100 h-100 animated-scaleX">
            <img src="{{ asset('/assets/images/dashboard/top-header3.png') }}" alt="header"
                class="theme-color-green-img img-fluid w-100 h-100 animated-scaleX">
            <img src="{{ asset('/assets/images/dashboard/top-header4.png') }}" alt="header"
                class="theme-color-yellow-img img-fluid w-100 h-100 animated-scaleX">
            <img src="{{ asset('/assets/images/dashboard/top-header5.png') }}" alt="header"
                class="theme-color-pink-img img-fluid w-100 h-100 animated-scaleX">
        </div>
    </div> <!-- Nav Header Component End -->
    <!--Nav End-->
</div>




{{-- <li class="nav-item dropdown">
    <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown">
        <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z"
                fill="currentColor"></path>
            <path opacity="0.4"
                d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z"
                fill="currentColor"></path>
        </svg>
        <span class="bg-danger dots"></span>
    </a>
    <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="notification-drop">
        <div class="m-0 shadow-none card">
            <div class="py-3 card-header d-flex justify-content-between bg-primary rounded-top">
                <div class="header-title">
                    <h5 class="mb-0 text-white">All Notifications</h5>
                </div>
            </div>
            <div class="p-0 card-body">
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <img class="p-1 avatar-40 rounded-pill bg-primary-subtle" src="../assets/images/shapes/01.png"
                            alt="">
                        <div class="ms-3 w-100">
                            <h6 class="mb-0 text-start iq-text">Emma Watson Bni</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0">95 MB</p>
                                <small class="float-end font-size-12">Just Now</small>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/02.png" alt="">
                        </div>
                        <div class="ms-3 w-100">
                            <h6 class="mb-0 text-start  iq-text">New customer is join</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0">Cyst Bni</p>
                                <small class="float-end font-size-12">5 days ago</small>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <img class="p-1 avatar-40 rounded-pill bg-primary-subtle" src="../assets/images/shapes/03.png"
                            alt="">
                        <div class="ms-3 w-100">
                            <h6 class="mb-0 text-start iq-text">Two customer is left</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0">Cyst Bni</p>
                                <small class="float-end font-size-12">2 days ago</small>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <img class="p-1 avatar-40 rounded-pill bg-primary-subtle" src="../assets/images/shapes/04.png"
                            alt="">
                        <div class="w-100 ms-3">
                            <h6 class="mb-0 text-start  iq-text">New Mail from Fenny</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0">Cyst Bni</p>
                                <small class="float-end font-size-12">3 days ago</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</li> --}}
{{-- <li class="nav-item dropdown">
    <a href="#" class="nav-link" id="mail-drop" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.4"
                d="M22 15.94C22 18.73 19.76 20.99 16.97 21H16.96H7.05C4.27 21 2 18.75 2 15.96V15.95C2 15.95 2.006 11.524 2.014 9.298C2.015 8.88 2.495 8.646 2.822 8.906C5.198 10.791 9.447 14.228 9.5 14.273C10.21 14.842 11.11 15.163 12.03 15.163C12.95 15.163 13.85 14.842 14.56 14.262C14.613 14.227 18.767 10.893 21.179 8.977C21.507 8.716 21.989 8.95 21.99 9.367C22 11.576 22 15.94 22 15.94Z"
                fill="currentColor"></path>
            <path
                d="M21.4759 5.67351C20.6099 4.04151 18.9059 2.99951 17.0299 2.99951H7.04988C5.17388 2.99951 3.46988 4.04151 2.60388 5.67351C2.40988 6.03851 2.50188 6.49351 2.82488 6.75151L10.2499 12.6905C10.7699 13.1105 11.3999 13.3195 12.0299 13.3195C12.0339 13.3195 12.0369 13.3195 12.0399 13.3195C12.0429 13.3195 12.0469 13.3195 12.0499 13.3195C12.6799 13.3195 13.3099 13.1105 13.8299 12.6905L21.2549 6.75151C21.5779 6.49351 21.6699 6.03851 21.4759 5.67351Z"
                fill="currentColor"></path>
        </svg>
        <span class="bg-primary count-mail"></span>
    </a>
    <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="mail-drop">
        <div class="m-0 shadow-none card">
            <div class="py-3 card-header d-flex justify-content-between bg-primary rounded-top">
                <div class="header-title">
                    <h5 class="mb-0 text-white">All Message</h5>
                </div>
            </div>
            <div class="p-0 card-body ">
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/01.png" alt="">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 ">Bni Emma Watson</h6>
                            <small class="float-start font-size-12">13 Jun</small>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/02.png" alt="">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 ">Lorem Ipsum Watson</h6>
                            <small class="float-start font-size-12">20 Apr</small>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/03.png" alt="">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 ">Why do we use it?</h6>
                            <small class="float-start font-size-12">30 Jun</small>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/04.png" alt="">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 ">Variations Passages</h6>
                            <small class="float-start font-size-12">12 Sep</small>
                        </div>
                    </div>
                </a>
                <a href="#" class="iq-sub-card">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                src="../assets/images/shapes/05.png" alt="">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 ">Lorem Ipsum generators</h6>
                            <small class="float-start font-size-12">5 Dec</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</li> --}}
