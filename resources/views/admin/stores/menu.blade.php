<div class="form-group">
    @foreach($categories as $cat)
        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="categories[]" value="{{ $cat->id }}"
                   id="cat_{{ $cat->id }}"
                    {{ in_array($cat->id, $selected) ? 'checked' : '' }}>
            <label class="form-check-label" for="cat_{{ $cat->id }}">
                {{ \App\Helper\LocalizationHelper::getNameByLocale($cat) }}
            </label>
        </div>
    @endforeach
</div>
