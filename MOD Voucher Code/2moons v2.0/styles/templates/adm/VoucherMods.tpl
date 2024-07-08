{include file="overall_header.tpl"}
<script type="text/javascript">
  function onGenerate() {
    var voucher = Math.random().toString(36).substring(2, 8).toUpperCase();
    document.getElementsByName("voucher")[0].value = voucher;
    document.getElementById("generate").style.display = "none";
    setTimeout(function () {
      document.getElementById("generate").style.display = "initial";
    }, 3000);
  }

  function openTabs(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }
</script>

<style>
  .tab {
    overflow: hidden;
    background-color: #171515b5;
    border-radius: 10px;
  }

  .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
  }

  .tab button:hover {
    background-color: #22352ead;
  }

  .tab button.active {
    background-color: #22352ead;
  }

  .tabcontent {
    display: none;
    padding: 6px 12px;
  }

  .tabcontent.active {
    display: block;
  }

  .content {
    margin: 0 auto;
    width: 50%;
    padding: 10px;
  }

  input:invalid,
  textarea:invalid {
    background-color: #211929;
  }

  input[type="submit"] {
    width: 20%;
    padding: 5px;
    border-radius: 5px;
    margin: 0 auto;
    text-align: center;
    background: #0e3c0eb2;
    border: none;
  }

  .label {
    padding: 5px;
    border-radius: 5px;
    color: white;
  }

  .label-success {
    background-color: #0e3c0eb2;
  }

  .label-danger {
    background-color: #3c0e0e;
  }

  .button {
    background: #0000007a;
    border: none;
  }

  .button.edit {
    color: #23379bb2;
  }

  .button.activate {
    color: #26b326b2;
  }

  .button.desactivate,
  .button.delete {
    color: #a22121;
  }

  .button:hover {
    cursor: pointer;
    border: none;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }
</style>
<div class="content">
  <div class="tab">
    <button class="tablinks" onclick="openTabs(event, 'create')">
      Create Voucher
    </button>
    <button class="tablinks" onclick="openTabs(event, 'listVoucher')">
      Voucher list
    </button>
    <button class="tablinks" onclick="openTabs(event, 'listVoucherUse')">
      Voucher list user use
    </button>
  </div>

  <div id="create" class="tabcontent active">
    <h1>Voucher Create</h1>
    <p>Generate a voucher code for a user.</p>
    <form action="" method="post">
      <input type="hidden" name="action" value="createVoucher" />
      <label for="voucher">Voucher:</label>
      <input id="voucher" type="text" name="voucher" value="" />
      <input
        id="generate"
        type="button"
        onclick="javascript: onGenerate()"
        value="Generate"
      />
      <br />
      <label for="start_time">Date de départ du code</label>
      <input id="start_time" type="datetime-local" name="start_time" value="" />
      <br />
      <label for="end_time">Date de fin du code</label>
      <p>Si vous le laisser vide il n'y aura pas de limite de temps</p>
      <input id="end_time" type="datetime-local" name="end_time" value="" />
      <br />
      <input type="submit" value="Create" />
    </form>

    <h1>Voucher Create Reward</h1>
    <p>Generate a voucher code for a user.</p>
    <form action="" method="post">
      <input type="hidden" name="action" value="createVoucherReward" />
      <label for="voucher">Voucher:</label>
      <select name="voucher_code" id="voucher_code">
        {foreach from=$vouchers item=voucher}
        <option value="{$voucher.id}">{$voucher.code}</option>
        {/foreach}
      </select>
      <br />
      <label for="reward">Reward:</label>
      <select name="reward" id="reward">
        {foreach from=$rewards item=reward}
        <option value="{$reward.id}">{$reward.name}</option>
        {/foreach}
      </select>
      <br />
      <label for="quantity">Quantity:</label>
      <input
        id="quantity"
        type="number"
        name="quantity"
        value=""
        placeholder="0"
        min="0"
      />
      <br />
      <input type="submit" value="Create" />
    </form>
  </div>

  <div id="listVoucher" class="tabcontent">
    <h1>Voucher List</h1>
    <table>
      <tr>
        <th>Voucher</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Used</th>
        <th>Actions</th>
      </tr>
      {foreach from=$vouchersList item=voucher}
      <tr id="{$voucher.id}">
        <td>{$voucher.code}</td>
        <td>{$voucher.start_time}</td>
        <td>{$voucher.end_time}</td>
        <td>
          {if $voucher.isComplete}
          <span class="label label-success">Open</span>
          {else}
          <span class="label label-danger">Finish</span>
          {/if}
        </td>
        <td>
          <form action="" method="post">
            <input type="hidden" name="action" value="editVoucher" />
            <input type="hidden" name="id" value="{$voucher.id}" />
            <button class="button edit" type="submit">
              <i class="fas fa-edit"></i>
            </button>
          </form>
          <form action="" method="post">
            <input type="hidden" name="action" value="deleteVoucher" />
            <input type="hidden" name="id" value="{$voucher.id}" />
            <button class="button delete" type="submit">
              <i class="fas fa-window-close"></i>
            </button>
          </form>
        </td>
      </tr>
      {/foreach}
    </table>
  </div>

  <div id="listVoucherUse" class="tabcontent">
    <h1>Voucher List User Use</h1>
    <table>
      <tr>
        <th>Voucher</th>
        <th>User</th>
        <th>Use time</th>
        <th>Actions</th>
      </tr>
      {foreach from=$rewardsUser item=voucher}
      <tr id="{$voucher.id}">
        <td>{$voucher.code}</td>
        <td>{$voucher.username}</td>
        <td>{$voucher.use_time}</td>
        <td>
          <form action="" method="post">
            <input type="hidden" name="action" value="deleteVoucherUse" />
            <input type="hidden" name="id" value="{$voucher.id}" />
            <button class="button delete" type="submit">
              <i class="fas fa-window-close"></i>
            </button>
          </form>
        </td>
      </tr>
      {/foreach}
    </table>
  </div>
</div>
{include file="overall_footer.tpl"}
