<table class="forum" cellpadding="0" cellspacing="{page:cellspacing}" style="width:{page:width}">
  <tr>
    <td class="headb" colspan="5">{lang:mod} - {lang:inbox}</td>
  </tr>
  <tr>
    <td class="leftb">{icon:mail_new} <a href="{url:messages_create}">{lang:new_message}</a></td>
    <td class="leftb">{icon:inbox} <a href="{url:messages_inbox}">{lang:inbox} {count:inbox}</a></td>
    <td class="leftb">{icon:outbox} <a href="{url:messages_outbox}">{lang:outbox} {count:outbox}</a></td>
    <td class="leftb">{icon:queue} <a href="{url:messages_archivbox}">{lang:archivbox} {count:archivbox}</a></td>
    <td class="rightb">{var:pages}</td>
  </tr>
  <tr>
    <td class="leftb">{icon:email} <a href="{url:messages_inbox:page=new}">{var:new_msgs} {lang:new_messages}</a></td>
    <td class="leftb" colspan="4">
    <form method="post" name="messages_filter" action="{url:messages_inbox}">
     <select name="messages_id" class="form">
      <option value="0">----</option>
      <option value="1">{lang:last_day}</option>
      <option value="2">{lang:last_2days}</option>
      <option value="3">{lang:last_4days}</option>
      <option value="4">{lang:last_week}</option>
      <option value="5">{lang:last_2weeks}</option>
      <option value="6">{lang:last_3weeks}</option>
      <option value="7">{lang:last_50days}</option>
      <option value="8">{lang:last_100days}</option>
      <option value="9">{lang:last_year}</option>
      <option value="10">{lang:last_2years}</option>
     </select> <input type="submit" name="submit" value="{lang:show}" class="form" /></form>
    </td>
  </tr>
</table>
<br />

<form method="post" name="messages_inbox" action="{url:messages_multiremove}">
<table class="forum" cellpadding="0" cellspacing="{page:cellspacing}" style="width:{page:width}">
  <tr>
    <td class="headb" style="width: 40px">{sort:view}</td>
    <td class="headb">{sort:subject} {lang:subject}</td>
    <td class="headb">{sort:sender} {lang:from}</td>
    <td class="headb">{sort:date} {lang:date}</td>
    <td class="headb" colspan="3" style="width: 80px">{lang:options}</td>
  </tr>{loop:msgs}{if:new_period}
  <tr>
    <td class="leftb" colspan="7">
    <div style="float: left">{msgs:period_name}</div>
    <div style="float: right">{lang:messages} {msgs:period_count}</div>
    </td>
  </tr>{stop:new_period}
  <tr>
    <td class="centerc">{icon:email}</td>
    <td class="leftc"><a href="{url:messages_view:id={msgs:messages_id}}">test</a></td>
    <td class="leftc"><a href="{url:users_view:id={msgs:users_id_from}}">duRiel</a></td>
    <td class="leftc">{msgs:messages_time}</td>
    <td class="centerc"><input type="checkbox" name="select_23" value="1" class="form" /></td>
    <td class="centerc"><a href="{url:messages_remove:id=23}" title="{lang:remove}">{icon:mail_delete}</a></td>
    <td class="centerc"><a href="{url:messages_archiv:id=23}" title="{lang:archiv}">{icon:ark}</a></td>
  </tr>{stop:msgs}
  <tr>
    <td class="rightb" colspan="7">
      <input type="button" name="sel_all" value="Alle markieren" onclick="return cs_shoutbox_select();" class="form" />
      <input type="submit" name="submit" value="Markierte entfernen" class="form" />
      <input type="reset" name="reset_sel" value="Markierung aufheben" class="form" />
     </td>
  </tr>
</table>
</form>
