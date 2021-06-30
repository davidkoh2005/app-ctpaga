<div class="tab-pane fade has-success" id="pills-email" role="tabpanel" aria-labelledby="pills-email-tab">
    <form id="formEmails" class="contact-form" method='POST' action="{{route('admin.settingsEmails')}}">    
        <div class="row">
            <h4 class="mx-auto">Ingrese la cuenta que recibirá el correo electrónico:</h4>
        </div>
        <div class="row">&nbsp;</div>
        <p><strong>Importante:</strong> Separar correo con coma ( <strong>,</strong> ) o utilizando tecla tabulador (<strong>TAB</strong>) o barra espaciadora (<strong>SPACE</strong>)</p>
        <div class="row">&nbsp;</div>
        <div class="mb-3 row">
            <label class="col-md-2 col-12  col-form-label">Transacciones:</label>
            <div class="col">
                <input class="form-control" type="text" id="emailsPaid" value="{{$emailsAllPaid? $emailsAllPaid->value : ''}}" autocomplete="off">
                <input type='hidden' id='emailsAllPaid' name='emailsAllPaid' class='form-control'>
                <!-- <textarea class="form-control" name="emailsPaid" id="emailsPaid" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
            </div>
        </div>
        <div class="row">&nbsp;</div>  
        <div class="mb-3 row">
            <label class="col-md-2 col-12 col-form-label">Delivery</label>
            <div class="col">
                <input class="form-control" type="text" id="emailsDelivery" value="{{$emailsAllDelivery? $emailsAllDelivery->value : ''}}" autocomplete="off">
                <input type='hidden' id='emailsAllDelivery' name='emailsAllDelivery' class='form-control'>
                <!-- <textarea class="form-control" name="emailsDelivery" id="emailsDelivery" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
            </div>
        </div>

        <div class="row">&nbsp;</div>  
        <div class="mb-3 row">
            <label class="col-md-2 col-12 col-form-label">Estado de pedido </label>
            <div class="col">
                <input class="form-control" type="text" id="statusPaid" value="{{$statusPaidAll? $statusPaidAll->value : ''}}" autocomplete="off">
                <input type='hidden' id='statusPaidAll' name='statusPaidAll' class='form-control'>
                <!-- <textarea class="form-control" name="emailsDelivery" id="emailsDelivery" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-6 mx-auto">
                <button type="submit" class="submit btn btn-bottom" id="submitEmail">Guardar</button>
            </div>
        </div>
    </form>
</div>