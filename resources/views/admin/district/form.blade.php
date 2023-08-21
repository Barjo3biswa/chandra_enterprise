<div class="row">
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <select class="form-control show-tick" name="state_id" id="state_id">
                <option value="">-- Please select state --</option>
                <?php foreach ($states as $state): ?>
                <option value="{{ $state->id }}" data-themeid="{{ $state->id }}" {{ old('state_id') == "$state->id" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
                <?php endforeach; ?> 
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                <label class="form-label">District</label>
            </div>
        </div>
    </div>                         
</div>
<button class="btn btn-primary waves-effect"  type="submit">SUBMIT</button>