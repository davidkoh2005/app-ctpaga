<div class="tab-pane fade show active" id="pills-delivery" role="tabpanel" aria-labelledby="pills-delivery-tab">
    <form id="formSchedule" class="contact-form" method='POST' action="{{route('admin.settingsSchedule')}}">
        <div class="row">
            <h4 class="mx-auto">Ingrese el horario de Delivery:</h4>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="row justify-content-center align-items-center minh-10">
            <div class="mb-3 row">
                <label class="col-md-2 col-12  col-form-label pt-3">Horario</label>
                <div class="col">
                    <label class="content-select">
                        <select class="addMargin" name="hoursInitial" id="hoursInitial">
                            @for ($hours=1; $hours<=12; $hours++) 
                                <option value="{{str_pad($hours,2,'0',STR_PAD_LEFT)}}">{{str_pad($hours,2,'0',STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                    </label>
                    <label style="color:black; font-size: 30px; padding-left:10px;"> : </label>
                    <label class="content-select">
                        <select class="addMargin" name="minInitial" id="minInitial">
                            @for ($mins=0; $mins<=59; $mins++) 
                                <option value="{{str_pad($mins,2,'0',STR_PAD_LEFT)}}">{{str_pad($mins,2,'0',STR_PAD_LEFT)}} </option>
                            @endfor
                        </select>
                    </label>
                    <label class="content-select">
                        <select class="addMargin" name="anteMeridiemInitial" id="anteMeridiemInitial">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="mb-3 row divUntil">
                <span id="until"> Hasta </span>
            </div>
            <div class="mb-3 row">
                <div class="col">
                    <label class="content-select">
                        <select class="addMargin" name="hoursFinal" id="hoursFinal">
                            @for ($hours=1; $hours<=12; $hours++) 
                                <option value="{{str_pad($hours,2,'0',STR_PAD_LEFT)}}">{{str_pad($hours,2,'0',STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                    </label>
                    <label style="color:black; font-size: 30px; padding-left:10px;"> : </label>
                    <label class="content-select">
                        <select class="addMargin" name="minFinal" id="minFinal">
                            @for ($mins=0; $mins<=59; $mins++) 
                                <option value="{{str_pad($mins,2,'0',STR_PAD_LEFT)}}">{{str_pad($mins,2,'0',STR_PAD_LEFT)}} </option>
                            @endfor
                        </select>
                    </label>
                    <label class="content-select">
                        <select class="addMargin" name="anteMeridiemFinal" id="anteMeridiemFinal">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-6 mx-auto">
                <button type="submit" class="submit btn btn-bottom" id="submitSchedule">Guardar</button>
            </div>
        </div>
    </form>
</div>