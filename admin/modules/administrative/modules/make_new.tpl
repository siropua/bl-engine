<table cellpadding="3" cellspacing="5" style="border:1px solid white">

<tr>
	<td bgcolor="White" valign="top">
	<!---- CREATING NEW SECTION ----->
<form action="?" method="post" enctype="multipart/form-data">
	<h4>{#creating_section#}</h4>
	<table>
		<tr>
			<td align="right">{#section_name#}:</td>
			<td><input type="text" maxlength="50" size="40" name="new_section" /></td>
		</tr>
		<tr>
			<td align="right">{#section_url#}:</td>
			<td><input type="text" maxlength="50" size="30" name="new_section_url"/></td>
		</tr>
		<tr>
			<td align="right">{#icon#}:</td>
			<td><input type="file" size="30" name="new_section_icon" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><button type="submit">{#create_section#}</button></td>
		</tr>
	</table>
</form>
	</td>
	
	<td bgcolor="White" valign="top">
		<!---- CREATING NEW MODULE ----->
		
<form action="?" method="post" enctype="multipart/form-data">
	<h4>{#creating_module#}</h4>
	<table>
		<tr>
			<td align="right">{#module_section#}:</td>
			<td>{html_options options=$module_sections name="new_module_section"}</td>
		</tr>
		<tr>
			<td align="right">{#module_name#}:</td>
			<td><input type="text" maxlength="50" size="40" name="new_module" /></td>
		</tr>
		<tr>
			<td align="right">{#module_url#}:</td>
			<td><input type="text" maxlength="50" size="30" name="new_module_url"/></td>
		</tr>
		<tr>
			<td align="right">{#icon#}:</td>
			<td><input type="file" size="30" name="new_module_icon" /></td>
		</tr>
		<tr>
			<td align="right"><input type="checkbox" name="create_files" value="1" id="create_files" checked/></td>
			<td><label for="create_files">{#create_base_files#}</label></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><button type="submit">{#create_module#}</button></td>
		</tr>
	</table>
</form>		
	</td>
</tr>
</table>