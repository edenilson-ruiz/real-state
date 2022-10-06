let $provincia, $canton, $distrito, $barrio;

$(function (){
    $provincia = $('#provincia');
    $canton = $('#canton');
    $distrito = $('#distrito');
    $barrio = $('#barrio');
    $provincia.on('change', () => {
        const provinciaId = $provincia.val();
        const url = `/api/cantones/${provinciaId}/provincias`;
        $.getJSON(url, onCantonesLoaded);
    });
    $canton.on('change', () => {
        const cantonId = $canton.val();
        const url = `/api/distritos/${cantonId}/cantones`;
        $.getJSON(url, onDistritosLoaded);
    });
    $distrito.on('change', () => {
        const distritoId = $distrito.val();
        const url = `/api/barrios/${distritoId}/distritos`;
        $.getJSON(url, onBarriosLoaded);
    });
});

function onCantonesLoaded(cantones) {
    let htmlOptions = `<select name="canton_id" id="canton" class="form-control">
                                <option value="">-- Seleccione un canton --</option>`;
    cantones.forEach(function (canton) {
       htmlOptions += `<option value="${canton.id}">${canton.name}</option>`;
    });
    htmlOptions += `</select>`;
    $canton.html(htmlOptions);
    $canton.selectpicker('refresh') ;
}

function onDistritosLoaded(distritos) {
    let htmlOptions = `<select name="distrito_id" id="distrito" class="form-control">
                                <option value="">-- Seleccione un distrito --</option>`;
    distritos.forEach(function (distrito) {
       htmlOptions += `<option value="${distrito.id}">${distrito.name}</option>`;
    });
    htmlOptions += `</select>`;
    $distrito.html(htmlOptions);
    $distrito.selectpicker('refresh') ;
}

function onBarriosLoaded(barrios) {
    let htmlOptions = `<select name="barrio_id" id="barrio" class="form-control">
                                <option value="">-- Seleccione un barrio --</option>`;
    barrios.forEach(function (barrio) {
       htmlOptions += `<option value="${barrio.id}">${barrio.name}</option>`;
    });
    htmlOptions += `</select>`;
    $barrio.html(htmlOptions);
    $barrio.selectpicker('refresh') ;
}