<div class="tab-pane fade has-success" id="pills-cost" role="tabpanel" aria-labelledby="pills-cost-tab">
    <form id="formCost" action="{{route('admin.settingsCosts')}}" method="post">
        <div class="row">
            <h4 class="mx-auto">Ingrese el costo de Delivery:</h4>
        </div>
        <div class="row">
            <label class="form"><strong>Estado:</strong></label>
            <label class="content-select">
                <select class="addMargin" name="selectState" id="selectState" required="" data-parsley-required-message="Debe Seleccionar un Estado" >
                    <option value="" selected>Seleccionar</option>
                </select>
            </label>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row showTextMunicipalities">
            <label class="form"><strong>Municipio:</strong></label>
        </div>
        <div class="row">&nbsp;</div>
        <div id="showCost" class="mx-auto row"></div>
        <div class="row">&nbsp;</div>
        <div class="row showTextMunicipalities" >
            <div class="col-6 mx-auto">
                <button type="submit" class="submit btn btn-bottom" id="submitCost">Guardar</button>
            </div>
        </div>
    </form>
</div>