<div class="form-group form-float">
    <div class="form-line">
        <select name="provincia_id" class="form-control show-tick" id="provincia" required>
            <option value="">-- Seleccione una provincia --</option>
            @foreach ($provincias as $provincia)
{{--                <option value="{{ $key }}" {{ (Input::old("title") == $key ? "selected":"") }}>{{ $val }}</option>--}}
                <option value="{{ $provincia->id }}" {{ ( old("provincia_id") == $provincia->id ? "selected":"") }}>{{ $provincia->name }}</option>
            @endforeach

        </select>
    </div>
</div>

<div class="form-group form-float">
    <div class="form-line">
        <select name="canton_id" class="form-control show-tick" id="canton" required>
            <option value="">-- Seleccione un canton --</option>
        </select>
    </div>
</div>


<div class="form-group form-float">
    <div class="form-line">
        <select name="distrito_id" class="form-control show-tick" id="distrito">
            <option value="">-- Seleccione un distrito --</option>
        </select>
    </div>
</div>

<div class="form-group form-float">
    <div class="form-line">
        <select name="barrio_id" class="form-control show-tick" id="barrio">
            <option value="">-- Seleccione un barrio --</option>
        </select>
    </div>
</div>
