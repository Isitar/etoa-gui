
function toggleBox(boxId)
{
	b = document.getElementById(boxId);
	if (b.style.display=='none')
	{
		b.style.display='';
	}
	else
	{
		b.style.display='none';
	}
}


			function banEnable(enbl)
			{
				if (enbl==true)
				{
					document.getElementById('user_blocked_from_d').style.backgroundColor='';
					document.getElementById('user_blocked_from_m').style.backgroundColor='';
					document.getElementById('user_blocked_from_y').style.backgroundColor='';
					document.getElementById('user_blocked_from_h').style.backgroundColor='';
					document.getElementById('user_blocked_from_i').style.backgroundColor='';
					document.getElementById('user_blocked_to_d').style.backgroundColor='';
					document.getElementById('user_blocked_to_m').style.backgroundColor='';
					document.getElementById('user_blocked_to_y').style.backgroundColor='';
					document.getElementById('user_blocked_to_h').style.backgroundColor='';
					document.getElementById('user_blocked_to_i').style.backgroundColor='';
					document.getElementById('user_ban_admin_id').style.backgroundColor='';
					document.getElementById('user_ban_reason').style.backgroundColor='';
					document.getElementById('user_blocked_from_d').disabled=false;
					document.getElementById('user_blocked_from_m').disabled=false;
					document.getElementById('user_blocked_from_y').disabled=false;
					document.getElementById('user_blocked_from_h').disabled=false;
					document.getElementById('user_blocked_from_i').disabled=false;
					document.getElementById('user_blocked_to_d').disabled=false;
					document.getElementById('user_blocked_to_m').disabled=false;
					document.getElementById('user_blocked_to_y').disabled=false;
					document.getElementById('user_blocked_to_h').disabled=false;
					document.getElementById('user_blocked_to_i').disabled=false;
					document.getElementById('user_ban_admin_id').disabled=false;
					document.getElementById('user_ban_reason').disabled=false;
				}
				else
				{
					document.getElementById('user_blocked_from_d').style.backgroundColor='#444';
					document.getElementById('user_blocked_from_m').style.backgroundColor='#444';
					document.getElementById('user_blocked_from_y').style.backgroundColor='#444';
					document.getElementById('user_blocked_from_h').style.backgroundColor='#444';
					document.getElementById('user_blocked_from_i').style.backgroundColor='#444';
					document.getElementById('user_blocked_to_d').style.backgroundColor='#444';
					document.getElementById('user_blocked_to_m').style.backgroundColor='#444';
					document.getElementById('user_blocked_to_y').style.backgroundColor='#444';
					document.getElementById('user_blocked_to_h').style.backgroundColor='#444';
					document.getElementById('user_blocked_to_i').style.backgroundColor='#444';
					document.getElementById('user_ban_admin_id').style.backgroundColor='#444';
					document.getElementById('user_ban_reason').style.backgroundColor='#444';
					document.getElementById('user_blocked_from_d').disabled=true;
					document.getElementById('user_blocked_from_m').disabled=true;
					document.getElementById('user_blocked_from_y').disabled=true;
					document.getElementById('user_blocked_from_h').disabled=true;
					document.getElementById('user_blocked_from_i').disabled=true;
					document.getElementById('user_blocked_to_d').disabled=true;
					document.getElementById('user_blocked_to_m').disabled=true;
					document.getElementById('user_blocked_to_y').disabled=true;
					document.getElementById('user_blocked_to_h').disabled=true;
					document.getElementById('user_blocked_to_i').disabled=true;
					document.getElementById('user_ban_admin_id').disabled=true;
					document.getElementById('user_ban_reason').disabled=true;
				}
			}
			function umodEnable(enbl)
			{
				if (enbl==true)
				{
					document.getElementById('user_hmode_from_d').style.backgroundColor='';
					document.getElementById('user_hmode_from_m').style.backgroundColor='';
					document.getElementById('user_hmode_from_y').style.backgroundColor='';
					document.getElementById('user_hmode_from_h').style.backgroundColor='';
					document.getElementById('user_hmode_from_i').style.backgroundColor='';
					document.getElementById('user_hmode_to_d').style.backgroundColor='';
					document.getElementById('user_hmode_to_m').style.backgroundColor='';
					document.getElementById('user_hmode_to_y').style.backgroundColor='';
					document.getElementById('user_hmode_to_h').style.backgroundColor='';
					document.getElementById('user_hmode_to_i').style.backgroundColor='';
					document.getElementById('user_hmode_from_d').disabled=false;
					document.getElementById('user_hmode_from_m').disabled=false;
					document.getElementById('user_hmode_from_y').disabled=false;
					document.getElementById('user_hmode_from_h').disabled=false;
					document.getElementById('user_hmode_from_i').disabled=false;
					document.getElementById('user_hmode_to_d').disabled=false;
					document.getElementById('user_hmode_to_m').disabled=false;
					document.getElementById('user_hmode_to_y').disabled=false;
					document.getElementById('user_hmode_to_h').disabled=false;
					document.getElementById('user_hmode_to_i').disabled=false;
				}
				else
				{
					document.getElementById('user_hmode_from_d').style.backgroundColor='#444';
					document.getElementById('user_hmode_from_m').style.backgroundColor='#444';
					document.getElementById('user_hmode_from_y').style.backgroundColor='#444';
					document.getElementById('user_hmode_from_h').style.backgroundColor='#444';
					document.getElementById('user_hmode_from_i').style.backgroundColor='#444';
					document.getElementById('user_hmode_to_d').style.backgroundColor='#444';
					document.getElementById('user_hmode_to_m').style.backgroundColor='#444';
					document.getElementById('user_hmode_to_y').style.backgroundColor='#444';
					document.getElementById('user_hmode_to_h').style.backgroundColor='#444';
					document.getElementById('user_hmode_to_i').style.backgroundColor='#444';
					document.getElementById('user_hmode_from_d').disabled=true;
					document.getElementById('user_hmode_from_m').disabled=true;
					document.getElementById('user_hmode_from_y').disabled=true;
					document.getElementById('user_hmode_from_h').disabled=true;
					document.getElementById('user_hmode_from_i').disabled=true;
					document.getElementById('user_hmode_to_d').disabled=true;
					document.getElementById('user_hmode_to_m').disabled=true;
					document.getElementById('user_hmode_to_y').disabled=true;
					document.getElementById('user_hmode_to_h').disabled=true;
					document.getElementById('user_hmode_to_i').disabled=true;
				}
			}
			
	function showLoader(elem)
	{
		document.getElementById(elem).innerHTML='<div style=\"text-align:center;padding:10px;\"><img src="../images/loadingmiddle.gif" /></div>';
	}

	function showLoaderPrepend(elem)
	{
		document.getElementById(elem).innerHTML='<div style=\"text-align:center;padding:10px;\"><img src="../images/loadingmiddle.gif" /></div>'+document.getElementById(elem).innerHTML;
	}
	