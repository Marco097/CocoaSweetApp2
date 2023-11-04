<div class="modal-inner-wrap" data-role="focusable-scope">
    <header class="modal-header">
        
        <h1 id="modal-title-24" class="modal-title" data-role="title">           
                Seleccioná tu ubicación
        </h1>
        
        <button class="action-close" data-role="closeBtn" type="button">
            <span>Cerrar</span>
        </button>
    </header>
    <div id="modal-content-24" class="modal-content" data-role="content"><div class="select-location-form">
<div class="content">
    <!-- ko foreach: getRegion('messages') -->
    <!-- ko template: getTemplate() -->
<div data-role="checkout-messages" class="messages" data-bind="visible: isVisible(), click: removeAll">
<!-- ko foreach: messageContainer.getErrorMessages() --><!--/ko-->
<!-- ko foreach: messageContainer.getSuccessMessages() --><!--/ko-->
</div>
<!-- /ko -->
    <!--/ko-->
    <p class="intro" data-bind="i18n: 'Delivery options and delivery speeds may vary for different locations.'">Las opciones y los tiempos de entrega pueden variar según las diferentes ubicaciones.</p>
    <!-- ko ifnot: isLoggedIn -->
    <form class="form form-login" data-bind="attr: { action: loginUrl }" method="get" action="">
        <fieldset class="fieldset login">
            <div class="actions-toolbar">
                <div class="primary">
                    <button type="submit" class="action login primary">
                        <span data-bind="i18n: 'Sign In to see your addresses'">Ingresar</span>
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
    <div class="form-separator">
        <span data-bind="i18n: 'Or'">O</span>
    </div>

    <form class="form form-location">
        <fieldset class="fieldset set-location">
            <div class="field note">
                <span data-bind="i18n: 'Please provide the following information find nearest inventory source.'">Proporcioná la siguiente información para encontrar la fuente de inventario más cercana.</span>
            </div>
            <div class="field field-select-state required">
                <label for="location_state_select" class="label"><span data-bind="i18n: 'State/Province'">Estado/Provincia</span></label>
                <div class="control select-input">
                    <select class="select" id="location_state_select" name="customer-select-state" data-validate="{'validate-select':true}" data-bind=" options: stateOptions,
                                        optionsValue: 'value',
                                        optionsText: 'text',
                                        optionsCaption: $t('Please select a State'),
                                        value: stateSelected"><option value="">Departamento</option><option value="3344">Ahuachapan</option><option value="3345">Cabañas</option><option value="3346">Chalatenango</option><option value="3347">Cuscatlan</option><option value="3348">La Libertad</option><option value="3349">La Paz</option><option value="3350">La Union</option><option value="3351">Morazan</option><option value="3352">San Miguel</option><option value="3353">San Salvador</option><option value="3354">San Vicente</option><option value="3355">Santa Ana</option><option value="3356">Sonsonate</option><option value="3357">Usulutan</option>
                    </select>
                </div>
            </div>
            <div class="field field-select-district required">
                <label for="location_district_select" class="label"><span data-bind="i18n: 'District'">Distrito</span></label>
                <div class="control select-input">
                    <select class="select" id="location_district_select" name="customer-select-district" data-validate="{'validate-select':true}" data-bind=" options: districtOptions,
                                        optionsValue: 'value',
                                        optionsText: 'text',
                                        optionsCaption: $t('Please select a District'),
                                        value: districtSelected"><option value="">Municipio</option>
                    </select>
                </div>
            </div>
            <div class="field field-select-zone required">
                <label for="location_zone_select" class="label"><span data-bind="i18n: 'Zone'">Zona</span></label>
                <div class="control select-input">
                    <select class="select" id="location_zone_select" name="customer-select-zone" data-validate="{'validate-select':true}" data-bind=" options: zoneOptions,
                                        optionsValue: 'value',
                                        optionsText: 'text',
                                        optionsCaption: $t('Please select a Zone'),
                                        value: zoneSelected"><option value="">Poblado</option>
                    </select>
                </div>
            </div>

        </fieldset>
    </form>
    <!-- /ko -->
</div>
</div></div>
    
    <footer class="modal-footer">
        
        <button class="action secondary action-hide-popup" type="button" data-role="action"><span>Cerrar</span></button>
        
        <button class="action primary action-save-location" type="button" data-role="action"><span>Guardar ubicación</span></button>
        
    </footer>
    
</div>