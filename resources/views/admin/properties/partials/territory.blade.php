<div class="form-group form-float">
    <div class="form-line">
        <select name="provincia_id" class="form-control" id="provincia" required>
            <option value="">-- Seleccione una provincia --</option>
            @foreach ($provincias as $provincia)
                <option value="{{ $provincia->id }}">{{ $provincia->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group form-float">
    <div class="form-line">
        <select name="canton_id" class="form-control" id="canton">
            <option value="">-- Seleccione un canton --</option>
        </select>
    </div>
</div>


<div class="form-group form-float">
    <div class="form-line">
        <select name="distrito_id" class="form-control" id="distrito">
            <option value="">-- Seleccione un distrito --</option>
        </select>
    </div>
</div>

<div class="form-group form-float">
    <div class="form-line">
        <select name="barrio_id" class="form-control" id="barrio">
            <option value="">-- Seleccione un barrio --</option>
        </select>
    </div>
</div>
