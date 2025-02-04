<table id ="table_id" class="table table-hover table-bordered">
<thead>
  <tr>
    <th> Tyre ID </th>
    <th> Brand </th>
    <th> Size </th>
    <th> Pattern </th>
    <th> LiSi </th>


    <th># in stock </th>
    <th style="width: 5%"></th>
  </tr>
</thead>
<tbody>
    <tr v-for="(item, index) in stock" v-if="item.in_stock>0" :id="index">

      <td class="text-center">@{{item.tyre_id}}</td>
      <td class="text-center">@{{item.brand}}</td>
      <td class="text-center">@{{item.size}}</td>
      <td class="text-center">@{{item.pattern}}</td>
      <td class="text-center">@{{item.lisi}}</td>

      <td class="text-center" :class="{'text-red' : helperStockLive(index)<0}">@{{helperStockLive(index)}}</td>
      <td style="width: 5%">
        <a class="text-success" @click="add(index)">
          <i class="icon-plus-circle-s fa-plus-circle"></i>
        </a>
      </td>
    </tr>
</tbody>
</table>
