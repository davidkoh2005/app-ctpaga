<div class="tab-pane fade has-success" id="pills-zelle" role="tabpanel" aria-labelledby="pills-zelle-tab">
    <form id="formCost" action="{{route('admin.settingsZelle')}}" method="post">
        <div class="mb-3 row">
            <label class="col-sm-4 col-form-label">Correo electronico</label>
            <div class="col">
                <input class="form-control" type="email" name="email" autocomplete="off"  minlength="4" value="{{$zelle != NULL ? $zelle->value : ''}}" required>
            </div>
        </div>
        <div class="col-6 mx-auto">
            <button type="submit" class="submit btn btn-bottom" id="submitCost">Guardar</button>
        </div>
    </form>
</div>