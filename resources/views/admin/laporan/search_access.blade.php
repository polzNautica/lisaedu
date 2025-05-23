@extends('layouts.main.index')
@section('container')
@section('style')
<style>
  ::-webkit-scrollbar {
    display: none;
  }

  @media screen and (min-width: 1320px) {
    #search {
      width: 250px;
    }

    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (max-width: 1320px) {
    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }

    .navbreadcrumb {
      font-size: small;
    }
  }
</style>
@endsection
<nav aria-label="breadcrumb" class="navbreadcrumb ">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/admin/laporan" class="text-primary">Laporan</a>
    </li>
    <li class="breadcrumb-item active">{{ $dataquiz->title }}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
        <div class="justify-content-start">
          <select data-url="{{ $dataquiz->slug }}" id="score-filter" class="form-select" style="border: 1px solid #d9dee3;">
            <option disabled selected>Filter Scores</option>
            <option value="tertinggi" @if(request('filter')==='tertinggi' ) selected @endif>Highest Scores</option>
            <option value="terendah" @if(request('filter')==='terendah' ) selected @endif>Lowest Scores</option>
          </select>
        </div>
        <div class="justify-content-end">
          <form action="/admin/laporan/{{ $dataquiz->slug }}/search">
            <div class="input-group">
              <input type="search" class="form-control" name="q" id="search" value="{{ request('q') }}" style="border: 1px solid #d9dee3;" placeholder="Search Users Data..." autocomplete="off" />
            </div>
          </form>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <div class="table-responsive text-nowrap" style="border-radius: 3px;">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                <th class="text-white">No</th>
                <th class="text-white">Full Name</th>
                <th class="text-white">Gender</th>
                <th class="text-white">Answered Questions</th>
                <th class="text-white">Questions Not Answered</th>
                <th class="text-white">Quiz Submission Date</th>
                <th class="text-white text-center">Score</th>
                <th class="text-white text-center">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($reports as $index => $data)
                <tr>
                  <td>{{ $reports->firstItem() + $index }}</td>
                  <td>{{ $data->user->name }}</td>
                  <td>@if($data->user->gender == 'Male')<span class="badge bg-label-primary fw-bold">Male</span>@else<span class="badge fw-bold" style="color: #ff6384 !important; background-color: #ffe5eb !important;">Female</span>@endif</td>
                  <td><i data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="right" title="Task Icon" class="bx bx-task bx-tada text-primary" style="font-size: 19px;"></i>&nbsp;<span class="badge bg-label-primary">{{ $data->answer_user->where('answer_id', !null)->count() }} Answered Questions</span></td>
                  <td>{!! (($data->quiz->load('question')->question->count() - $data->answer_user->where('answer_id', !null)->count()) > 0) ? '<i data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="right" title="Danger Icon" class="bx bx-error bx-flashing text-danger" style="font-size: 19px;"></i>&nbsp;<span class="badge bg-label-danger">'. $data->quiz->load('question')->question->count() - $data->answer_user->where('answer_id', !null)->count() . ' Unanswered questions</span>' : '<i data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="right" title="Check Icon" class="bx bx-check-double bx-tada text-success" style="font-size: 20px;"></i>&nbsp;<span class="badge bg-label-dark">All Answered</span>' !!}</td>
                  <td>{{ $data->created_at->locale('en')->isoFormat('D MMMM YYYY | H:mm') }}</td>
                  <td class="text-center"><span class="badge badge-center bg-dark rounded-pill">{{ $data->score }}</span></td>
                  <td class="text-center">
                    <button type="button" class="btn btn-icon btn-primary btn-sm" onclick="window.location.href='/admin/laporan/details/{{ $data->code }}'" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Details">
                      <span class="tf-icons bx bx-show" style="font-size: 15px;"></span>
                    </button>
                  </td>
                </tr>
                @endforeach
                @if($reports->isEmpty())
                <tr>
                  <td colspan="100" class="text-center">Data tidak ditemukan dengan keyword pencarian: <b>"{{request('q')}}"</b></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </ul>
        @if(!$reports->isEmpty())
        <div class="mt-3 pagination-mobile">{{ $reports->withQueryString()->onEachSide(1)->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</div>
@section('script')
<script>
  $("#score-filter").on("change", function() {
    var selectedOption = this.value;
    var url = $("#score-filter").data('url');
    if (selectedOption === "tertinggi") {
      window.location.href = "/admin/laporan/" + url + "?filter=tertinggi";
    } else if (selectedOption === "terendah") {
      window.location.href = "/admin/laporan/" + url + "?filter=terendah";
    }
  });
</script>
@endsection
@endsection