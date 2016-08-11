<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#createArticleModal">Создать статью</button>

<!-- Modal -->
<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Создание статьи</h4>
      </div>
      <form action="{$SELF}" class="form" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label>Заголовок</label>
            <input type="text" class="form-control" name="newitem[title]">
          </div>
          <div class="form-group">
            <label>Каталог</label>
            <select name="newitem[catalog_id]" class="form-control">
              <option value="">Вне каталога</option>
              {foreach from=$catalogItems item=i}
              <option value="{$i.id}"> {$i.padding} {$i.title}</option>
              {/foreach}
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          <button type="submit" class="btn btn-success">Создать статью</button>
        </div>
      </form>
    </div>
  </div>
</div>

<table class="table">
  <thead>
    <tr>
      <th>Дата</th>
      <th>Статья</th>
      <th>Раздел</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$articles item=a}
    <tr id="tr{$a.id}" data-id="{$a.id}">
      <td>{$a.date_add|FormatDateTime}</td>
      <td><strong><a href="?id={$a.id}">{$a.title}</a></strong>
        {if $a.url}<div><small><a href="{$ROOT}{$a.url}">{$ROOT}{$a.url}</a></small></div>{/if}
      </td>
      <td>{if $a.catalog_id}{$a.catalog_title}{else}Без раздела{/if}</td>
      <td></td>
      <td></td>
      <td>
      <a href="?id={$a.id}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i></a>
      <button class="btn btn-xs btn-danger btn-delete"><i class="fa fa-trash"></i></button></td>
    </tr>
    {/foreach}
  </tbody>
</table>