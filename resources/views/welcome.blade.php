<div class="d-flex  justify-content-between gap-2 flex-row-reverse">
    <div class="col-lg-5 col-md-5 col-12  text-center ">
        <img src="{{ $users[0]['image']}}" width="285" height="285" class="rounded-circle mx-2 avatar-xl object-cover">
    </div>
    <div class="col-lg-6 col-md-6 col-12 py-3 px-2">
        <div class="row g-3 fs-3">
            <div>
                <span class="fw-bold ">ناو : </span>
                <span class="fw-normal "> {{ $users[0]['name'] }}</span>
            </div>
            <div>
                <span class="fw-bold ">نازناو :</span>
                <span class="fw-normal ">{{ $users[0]['username'] }}</span>
            </div>
            <div>
                <span class="fw-bold ">ئێمەیڵ :</span>
                <span class="fw-normal ">{{ $users[0]['email'] }}</span>
            </div>
            <div>
                <span class="fw-bold ">ژمارەی تەلەفۆن :</span>
                <span class="fw-normal ">{{ $users[0]['email'] }}</span>
            </div>
            <div>
                <span class="fw-bold ">پلە:</span>
                <span class="fw-normal ">{{ $users[0]['role'] }}</span>
            </div>
            <div>
                <span class="fw-bold ">دۆخ :</span>
                <span class="fw-normal ">{{ $users[0]['status'] }}</span>
            </div>
        </div>
    </div>
</div>
