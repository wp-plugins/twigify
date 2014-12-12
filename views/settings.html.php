<div class="wrap">
  <h2>Twigify Content Templates</h2>
  <div id="notices"></div>
  <table class="widefat" id="settings_table">
    <thead>
      <tr>
        <th>Administration Settings</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <form class="form settings-form form-inline" id="twigify-settings" method="post" action="">
          <table class="form-table">
            <tr>
              <th scope="row"><strong>Capabilities</strong><br/>
                <span class="description">Capabilies allowed to edit templates.</span>
                </th>
              <td>
                <fieldset>
                  {% for role in roles %}
                    <input type="checkbox" id='roles' name="twigify[roles][]" rel="roles" value="{{ role.name |lower }}" {{ role.checked }}/> {{ role.name }} <br/>
                  {% endfor %}
                  </fieldset>
              </td>
          </table>

          <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
          </form>
        </td>
      </tr>
    </tbody>
  </table>
