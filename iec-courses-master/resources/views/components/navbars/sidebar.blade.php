@props(['activePage'])

<style>
    .custom-active {
        color: #fff;
        background-color: #3490dc;
    }

</style>
<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logos/iec-Logo.png') }}" class="navbar-brand-img h-100" alt="IEC Logo">
            <span class="ms-3 font-weight-bold text-white sidebar-brand-text">IEC Courses</span>
        </a>
    </div>


    <hr class="horizontal light mt-0 mb-2">
    <div class="  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">

    <div class="sidenav-footer position-absolute w-0 bottom-0 ">

    </div>
</aside>
