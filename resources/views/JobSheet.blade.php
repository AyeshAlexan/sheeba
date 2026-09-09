@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <title>Job Sheet</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Job Sheet</h4>
                            </div>
                            <hr style="height: 5px; color: blue;">

                            <div class="card-body">
                                {{-- alerts section --}}
                                @if (session('delete'))
                                <div class="alert alert-danger text-center" role="alert">
                                    {{session('delete')}} &#10004;
                                </div>
                                @endif
                                @if (session('added'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{session('added')}} &#10004;
                                </div>
                                @endif
                                @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (Session::has('done'))
                                <div class="alert alert-success text-center">
                                    <p>{{ Session::get('done') }}</p>
                                </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            var pdfLink = "{{ Session::get('pdfLink') }}";
                                            window.open(pdfLink, '_blank');
                                        });
                                    </script>
                                @endif

                                <div class="row ">

                                    <form action="{{ route("create_jobsheet") }}" method="post">
                                        @csrf
                                        {{-- form top row --}}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="customer_phone"> Tel :
                                                    <div class="input-group">
                                                        <input type="text" class="form-control  form-control-lg"
                                                            name="customer_phone" id="searchCustomer"
                                                            placeholder="Enter Customer Tel" required />
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-success btn-lg form-control "
                                                                data-bs-toggle="modal" data-bs-target="#addCustomerModel">
                                                                <i class="fas fa-plus" style="color: white"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="col-md-4 ">
                                                <div class="showCustomer">
                                                    <label for="customer_name"> Customer Name :
                                                        <div class="input-group">
                                                            <input type="text" class="form-control  form-control-lg"
                                                                name="customer_name" id="customer_name"
                                                                placeholder="Enter Customer Name" required />

                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <label for="job_no">Job No :
                                                    <input class="form-control" value="{{ $maxJobSheetNo+1}}" type="text" placeholder="Job Number:" id="job_no"
                                                        name="job_no" required>
                                                </label>
                                            </div>

                                            <div class="col">
                                                <label for="date">Date :
                                                    <input class="form-control datetimepicker" type="date" id="receipt_date"
                                                        name="receipt_date" required>
                                                </label>
                                            </div>
                                        </div>
                                        <hr>

                                        {{-- Brand Model IMEI Section --}}
                                        <div class="row">
                                            {{-- second row --}}
                                            <div class="row mt-2 mb-3">
                                                {{-- brand --}}
                                                <div class="col-sm-4">
                                                    {{-- <div class="form-group">
                                                        <label for="brand">Brand:</label>
                                                        <div class="input-group">
                                                            <select class="form-control select2" id="brand" name="brand">
                                                                <option selected="selected" value="">Please Select</option>
                                                                <option value="Samsung">Samsung</option>
                                                                <option value="Nokia">Nokia</option>
                                                            </select>
                                                            <span class="input-group-btn">
                                                                <button type="button"
                                                                    class="btn btn-default bg-white btn-flat btn-modal"
                                                                    data-bs-toggle="modal" data-bs-target="#brand">
                                                                    <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label for="brand">Brand:</label>
                                                        <div class="input-group">
                                                            <input type="text" name="brand" id="brand" class="form-control"
                                                                placeholder="Enter Brand Name" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- model for brand
                                                <div class="modal fade" id="brand" tabindex="-1" aria-labelledby="brandLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="brandLabel">Modal title</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close">
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <div class="form-floating">
                                                                    <label for="floatingTextarea">Short description:</label>
                                                                    <textarea class="form-control" placeholder="Leave a comment here"
                                                                        id="floatingTextarea"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div> --}}

                                                {{--Phone Model--}}
                                                <div class="col-sm-4">
                                                    {{-- <div class="form-group">
                                                        <label for="device_model">Device Model:</label>
                                                        <div class="input-group">
                                                            <select class="form-control select2" id="device_model" name="device_model">
                                                                <option selected="selected" value="">Please Select</option>
                                                                <option value="S04">S04</option>
                                                                <option value="S10">S10</option>
                                                            </select>
                                                            <span class="input-group-btn">
                                                                <button type="button"
                                                                    class="btn btn-default bg-white btn-flat btn-modal"
                                                                    data-bs-toggle="modal" data-bs-target="#Device_Model">
                                                                    <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label for="device_model">Device Model :</label>
                                                        <div class="input-group">
                                                            <input type="text" name="device_model" id="device_model" class="form-control"
                                                                placeholder="Enter Device Model" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- model for phone model
                                                <div class="modal fade" id="Device_Model" tabindex="-1"
                                                    aria-labelledby="Device_ModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="Device ModelLabel">Modal title</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Device Model
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                {{-- Device Modal --}}
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="imei_number">IMEI Number:</label>
                                                        <div class="input-group">
                                                            <input type="text" name="imei_number" id="imei_number" class="form-control"
                                                                placeholder="Enter IMEI Number" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- checkbox first --}}
                                            <div class="row mt-2 mb-3">
                                                <div class="row ml-5">
                                                    <div class="col" >
                                                        <div class="form-check form-check-inline ">
                                                            <input class="form-check-input" type="checkbox" id="battery" value="Battery" name="items[]">
                                                            <label class="form-check-label"  for="inlineCheckbox1">Battery</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="charger" value="Charger" name="items[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Charger</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="memory_card" value="Memory Card" name="items[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Memory Card</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="sim" value="Sim" name="items[]">
                                                            <label class="form-check-label" for="inlineCheckbox1">Sim</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="back_cover" value="Back Cover" name="items[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Back Cover</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        {{-- <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="other" value="Other" name="items[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Other</label>
                                                        </div> --}}
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="text" name="items[]" id="other" class="form-control"
                                                                    placeholder="Other" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- payment section --}}
                                            <div class="row mt-2 mb-3">
                                                <div class="col">
                                                    <label for="amount">AMOUNT :
                                                        <input class="form-control form-control-lg"
                                                            style="font-weight:bold;" type="text"
                                                            placeholder="AMOUNT:" id="amount" name="amount">
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <label for="amount">ADVANCE :
                                                        <input style="font-weight:bold;"
                                                            class="form-control form-control-lg" type="text"
                                                            placeholder="ADVANCE:" id="advance"
                                                            name="advance" >
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <label for="amount">BALANCE :
                                                        <input style="font-weight:bold;"
                                                            class="form-control form-control-lg" type="text"
                                                            placeholder="BALANCE:" id="balance"
                                                            name="balance">
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- checkbox second section --}}
                                            <div class="row mt-2 mb-3">
                                                <div class="row ml-5">
                                                    <div class="col" >
                                                        <div class="form-check form-check-inline ">
                                                            <input class="form-check-input" type="checkbox" id="mic" value="Mic" name="problems[]">
                                                            <label class="form-check-label"  for="inlineCheckbox1">Mic</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="ringer" value="Ringer" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Ringer</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="ear_piece" value="Ear piece" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Ear piece</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="no_power" value="No power" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox1">No power</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="software" value="Software" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Software</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="SIM_connector" value="SIM connector" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">SIM connector</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="charging_pin" value="Charging pin" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox1">Charging pin</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="lock" value="Lock" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Lock</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="water_damage" value="Water damage" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Water damage</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="display" value="Display" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox1">Display</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="country_lock" value="Country Lock" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Country Lock</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="ribbon" value="Ribbon" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Ribbon</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="touch" value="Touch" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox1">Touch</label>
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="B/_pin" value="B/ pin" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">B/ pin</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="short" value="Short" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Short</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="battery" value="Battery" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">Battery</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="ic" value="IC" name="problems[]">
                                                            <label class="form-check-label" for="inlineCheckbox2">IC</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="text" name="problems[]" id="other" class="form-control"
                                                                    placeholder="Other" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- row for password details --}}
                                            <div class="row mt-2 mb-3">
                                                <div class="col 6">
                                                    <label for="Product Configuration" class="form-label">
                                                        Product Configuration :
                                                    </label>
                                                    <input type="text" class="form-control" id="product_configuration"
                                                    name="product_configuration" placeholder="Product Configuration"/>
                                                </div>
                                                <div class="col 6">
                                                    <label for="password" class="form-label"> Password :</label>
                                                    <input type="text" class="form-control" id="password" name="password"
                                                        placeholder="Password">
                                                </div>
                                            </div>


                                            {{-- row for form footer  --}}
                                            <div class="row mt-2 mb-2">

                                                <div class="col">
                                                    <label for="Problem Reported" class="form-label">
                                                        Problem Reported By The customer :
                                                    </label>
                                                    <input type="text" class="form-control" id="problem_reported"
                                                    name="problem_reported" placeholder="Problem Reported By The customer"/>
                                                </div>
                                                <div class="col">
                                                    <label for="product_condition" class="form-label">
                                                        Technician :
                                                    </label>
                                                    <input type="text" class="form-control" id="technician" name="technician"
                                                        placeholder="Technician"/>
                                                </div>
                                            </div>

                                            {{-- row for buttons --}}
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-6">
                                                    <br>
                                                    <button type="submit" name="save"
                                                        class="btn btn-outline-info btn-lg shadow">SAVE</button>
                                                    <button type="button" name="print"
                                                        class="btn btn-outline-primary printReceipt btn-lg shadow">PRINT</button>
                                                    <button type="button" name="pawn_delete" id="pawn_delete"
                                                        class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                                    <button type="reset" name="reset"
                                                        class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>




                                {{--------------Add customer model----------------- --}}
                                <div class="modal fade" id="addCustomerModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title m-2" id="myLargeModalLabel"> Add Customers </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="errMsgContainer"></div>
                                                            <form action="" method="post" id="addCustomer">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Code <span
                                                                                    style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                :</label>
                                                                            <input type="text" name="code"
                                                                                value="{{ $maxCustomer+1}}"
                                                                                id="code" class="form-control" placeholder="Customer Code"
                                                                                required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Title <span
                                                                                    style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                :</label>
                                                                            <div class=" form-group">
                                                                                <select class="select form-control" name="title" id="title"
                                                                                    aria-hidden="true" required>
                                                                                    <option value="">Please Select
                                                                                    </option>
                                                                                    <option value="Mr.">Mr.</option>
                                                                                    <option value="Mrs.">Mrs.
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Gender <span
                                                                                    style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                :</label>
                                                                            <div class=" form-group">
                                                                                <select class="select form-control" name="gender" id="gender"
                                                                                    aria-hidden="true" required>
                                                                                    <option value="">Please Select
                                                                                    </option>
                                                                                    <option value="Male">Male
                                                                                    </option>
                                                                                    <option value="Female">Female
                                                                                    </option>
                                                                                    <option value="Other">Other
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label>First Name <span
                                                                                            style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                        :</label>
                                                                                    <input type="text" name="first_name" id="first_name"
                                                                                        class="form-control" placeholder="First Name" required>
                                                                                </div>
                                                                                <div class="col">
                                                                                    <label>Middle Name :</label>
                                                                                    <input type="text" name="middle_name" id="middle_name"
                                                                                        class="form-control" placeholder="Middle Name">
                                                                                </div>
                                                                                <div class="col">
                                                                                    <label>Last Name
                                                                                        :</label>
                                                                                    <input type="text" name="last_name" id="last_name"
                                                                                        class="form-control" placeholder="Last Name" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <textarea class="form-control" name="address1" id="address1"
                                                                                rows="3" placeholder="Address-1:" required></textarea> <br>
                                                                            <textarea class="form-control" name="city1" id="city1" rows="1"
                                                                                placeholder="City-1 :"></textarea>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <textarea class="form-control" name="address2" id="address2"
                                                                                rows="3" placeholder="Address-2:"></textarea>
                                                                            <br>
                                                                            <textarea class="form-control" name="city2" id="city2" rows="1"
                                                                                placeholder="City-2 :"></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6">
                                                                            <label>Contact-1 <span
                                                                                    style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                :</label>
                                                                            <input type="text" name="contact1" id="contact1"
                                                                                class="form-control" placeholder="Contact 1" required>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Contact-2 :</label>
                                                                            <input type="text" name="contact2" id="contact2"
                                                                                class="form-control" placeholder="Contact 2">
                                                                        </div>
                                                                    </div>


                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6">
                                                                            <label>NIC :</label>
                                                                            <input type="text" name="nic" id="nic" class="form-control"
                                                                                placeholder="NIC" required>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Email :</label>
                                                                            <input type="text" name="email" id="email" class="form-control"
                                                                                placeholder="Email">
                                                                        </div>
                                                                    </div>


                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6">
                                                                            <label>Driving License :</label>
                                                                            <input type="text" name="driving_license" id="driving_license"
                                                                                class="form-control" placeholder="Driving Licence">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Passport :</label>
                                                                            <input type="text" name="passport" id="passport"
                                                                                class="form-control" placeholder="Passport">
                                                                        </div>

                                                                    </div>


                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6">
                                                                            <label>Other Identifications :</label>
                                                                            <input type="text" name="other_identifications"
                                                                                id="other_identifications" class="form-control"
                                                                                placeholder="Other Identifications">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="row">
                                                                                <div class="col-md-11">
                                                                                    <label>Mark as Active or
                                                                                        Blacklisted <span
                                                                                            style="color:#FF0000; font-weight: bold; ">*</span>
                                                                                        :</label>
                                                                                    <div class=" form-group">
                                                                                        <select class="select form-control" name="status"
                                                                                            id="status" aria-hidden="true" required>
                                                                                            <option value="">Please Select</option>
                                                                                            <option value="1" selected>
                                                                                                <span style="color:#16ec28;">
                                                                                                    Active
                                                                                                </span>
                                                                                            </option>
                                                                                            <option value="0">
                                                                                                <span style="color:#df0c0c;">
                                                                                                    Blacklist
                                                                                                </span>
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="text-center mt-4">
                                                                        <button type="button"
                                                                            class="btn btn-success add_customer bg-success-light text-success me-2">Save</button>
                                                                        <button type="button" class="btn btn-outline-secondary"
                                                                            data-bs-dismiss="modal" aria-label="Close">Close</button>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    </div>
                                </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>



    {!! Toastr::message() !!}

    {{-- form default date set for today --}}
    <script>
        document.getElementById('receipt_date').valueAsDate = new Date();
    </script>

    {{-- CSRF Token --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

     {{-- value auto calculation --}}
     <script>
        $(document).ready(function () {
            // Listen for changes in the amount and advance fields
            $('#amount, #advance').on('input', function () {
                // Get values from the Weight and QTY fields
                let amount = parseFloat($('#amount').val()) || 0;
                let advance = parseInt($('#advance').val()) || 0;

                // Retrieve the karatage rate from the selected option
                // let karatageRate = parseFloat($('#karatage').find(':selected').data('pawningrate')) ||
                // 0;
                // Calculate the value
                let balance = (amount - advance).toFixed(2);

                // Update the Value field
                $('#balance').val(balance);
            });
        });
    </script>

     {{--  get customer data inserting NIC --}}
     <script>
        $(document).ready(function () {
            // search customer data
            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#searchCustomer').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_customer_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (res) {
                                $('.showCustomer').html(res);

                                if (res.status == 'not_found') {
                                    $('.showCustomer').html(
                                        `
                                            <label for="customer_name" class="text-danger"> Customer Name :
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-danger form-control-lg"
                                                        name="customer_name" id="customer_name"
                                                        placeholder="Customer Not Found ..!!" required />
                                                </div>
                                            </label>
                                                    `
                                    );

                                }
                            }
                        });
                    }
                }
            })
        });

    </script>

    {{-- add new customer script --}}
    <script>
        $(document).ready(function () {
            //add new customer
            $(document).on('click', '.add_customer', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let title = $('#title').val();
                let gender = $('#gender').val();
                let name = $('#name').val();
                let first_name = $('#first_name').val();
                let middle_name = $('#middle_name').val();
                let last_name = $('#last_name').val();
                let address1 = $('#address1').val();
                let city1 = $('#city1').val();
                let address2 = $('#address2').val();
                let city2 = $('#city2').val();
                let contact1 = $('#contact1').val();
                let contact2 = $('#contact2').val();
                let email = $('#email').val();
                let nic = $('#nic').val();
                let driving_license = $('#driving_license').val();
                let passport = $('#passport').val();
                let other_identifications = $('#other_identifications').val();
                let status = $('#status').val();

                $.ajax({
                    url: "{{ route('add_customer_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        title: title,
                        gender: gender,
                        name: name,
                        first_name: first_name,
                        middle_name: middle_name,
                        last_name: last_name,
                        address1: address1,
                        city1: city1,
                        address2: address2,
                        city2: city2,
                        contact1: contact1,
                        contact2: contact2,
                        email: email,
                        nic: nic,
                        driving_license: driving_license,
                        passport: passport,
                        other_identifications: other_identifications,
                        status: status
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addCustomerModel").modal('hide');
                            $('#addCustomer')[0].reset();

                            // get customer data
                            let data = res.telNo;
                            let cus_details = data[0];
                            let cus_tel = cus_details.Contact_1;
                            let cus_name = cus_details.First_name;

                            $('#searchCustomer').val(cus_tel);
                            // $('#customer_name').val(cus_name);

                            // $('.table').load(location.href+' .table');
                            Command: toastr["success"]("Customer Added ...!", "Success")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }


                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');
                        });
                    }
                })
            })
        });

    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/feather.min.js"></script>
{{-- <script src="assets/plugins/select2/js/select2.min.js"></script> --}}
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
@endsection
