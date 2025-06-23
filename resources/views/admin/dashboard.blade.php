@extends('admin.layouts.main')

@section('importheadAppend')
    <style>
        .main-menu {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .main-menu li {
            margin: 10px;
            width: 150px;
            height: 150px;
            background: #036086;
            color: #fff;
            border-radius: 15px;
        }

        .main-menu li a {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 15px;
            width: 100%;
            height: 100%;
        }

        .main-menu li a i {
            display: block;
            font-size: 45px;
            margin-bottom: 10px;
        }

        .main-menu li a span {
            text-align: center;
            line-height: 20px;
        }

        .main-menu li a img {
            height: 100px;
            width: 100px;
            background: #fff;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .main-menu li a h1 {
            font-size: 14px;
        }

        @media (max-width: 575.98px) {
            .main-menu {
                margin: 15px -15px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Dashboard</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><i class="fa-solid fa-house"></i> Home</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <ul class="main-menu">
            <li>
                <a href="admin/profile">
                    <img src="{{ !empty(auth()->user()->image) ? 'avatar/' . auth()->user()->image : 'images/man.png' }}" alt="">
                </a>
            </li>
            @if (auth()->user()->hasPermissionTo('settings') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/settings"><i class="fa-solid fa-wrench"></i> <span>Settings</span></a></li>
            @endif
            @if (auth()->user()->hasPermissionTo('user-list') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/users"><i class="fa-solid fa-user-gear"></i> <span>Users</span></a></li>
            @endif
            @if (auth()->user()->hasPermissionTo('bidang-list') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/bidang"><i class="fa-solid fa-users"></i> <span>Bidang</span></a></li>
            @endif
            @if (auth()->user()->hasPermissionTo('unit-list') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/unit"><i class="fa-solid fa-users"></i> <span>Unit</span></a></li>
            @endif
            @if (auth()->user()->hasPermissionTo('sub_unit-list') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/subunit"><i class="fa-solid fa-users"></i> <span>Sub Unit</span></a></li>
            @endif
            @if (auth()->user()->hasPermissionTo('upb-list') || auth()->user()->hasRole('Super Admin'))
                <li><a href="admin/upb"><i class="fa-solid fa-users"></i> <span>UPB</span></a></li>
            @endif
        </ul>
    </div>
@endsection

@section('importfootAppend')
@endsection
