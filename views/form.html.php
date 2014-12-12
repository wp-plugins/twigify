<div id="rules-field">
  <div class="rules-repeater">
    <div class="rules">
      <label for="rule-context">Rule Context</label>
      <select id="rule-context" name="rules[][context]" >
        <option value="--">-Select-</option>
          {% for option in options %}
            <option value="{{ option.value }}">{{option.label}}</option>
          {% endfor %}
      </select>
      <div id="rule-context">
        <label for="rule-value">Rule Value</label>
        <input type="text" name="rules[][value]" value="" />
      </div>
    </div>
  </div>
</div>
