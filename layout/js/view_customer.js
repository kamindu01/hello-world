//console.log(logged_agent_id);
var selected_agent;
var agent_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th></tr>";
var customer_table = "<table class='table table-responsive table-bordered table-striped' style = 'width:400px'><tr><th>Username</th><th>Phone Number</th><th>Image</th><th>Member Id</th><th>Disable Customee</th><th>Change Password</th><th>Select Customer</th></tr>";
var lvl2agents_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th></tr>";

if(logged_agent_level == 1){

		if(action == 'agent'){

			$("#cus").hide();
		$("#agent").removeClass('col-md-5');
		get_database('super',logged_agent_id,'superagent');
	}

	else{
		get_database('super',logged_agent_id,'super');

	}


}

else{

		if(action == 'agent'){

		$("#cus").hide();
		$("#agent").removeClass('col-md-5');
		get_database('agent',logged_agent_id,'agent');
	}

	else{

		alter_agent(logged_agent_id,logged_agent_username,logged_agent_code);
		get_database('agent',logged_agent_id,'agent');
		select_customer(logged_agent_id);

	}
}

function alter_agent(id,username,code){

//	console.log(id+username+code);

	agent_table = agent_table+"<tr class='success' id='tr"+id+"' onclick = 'select_customer("+id+")'><td>"+id+"</td><td>"+username+"</td><td>"+code+"</td></tr>";

}

function back_super(){

	location.reload();
}


function select_super(id){

	$("#select_lvl2").hide();
	$("#select_lvl2_header").hide();
	$("#back_super").show();

get_database('first',id,'user');
get_database('agent',id,'agent');
		select_customer(id);
		

$("#cus_dta").show();
	

}

function select_customer(user){

get_database('customer',user,'customer');

}

function get_database(action,user,lvl){

	//console.log(user);
	 $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "get_customer.php",
                    data: {'tag':action,'user':user},
                    cache: false,
                    success: function(data)
                    {

                    	//console.log(data['agent_code']);


                    	if(lvl=='agent'|| lvl=='superagent' ){create_agent_table(data); }

                    	else if(lvl=='customer'){create_customer_table(data,user);}

                    	else if(lvl =='super'){create_super_select(data)}

                    	else if(lvl == 'user'){alter_agent(data['id'],data['agent_username'],data['agent_code']);}
						
						else if(lvl == 'username'){ $("#customer_table_header").text(data['agent_username']+'\'s Customers'); }
  
                    },
                    async:true
                });
}


function create_agent_table(data){

	//console.log(data);

	if(action == 'agent'){

		var disable;

			var ag_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th><th>Phone Number</th><th>Image</th><th>Disable Agent</th><th>Select Agent</th></tr>";

			$.each(data , function(index , value){

				if(data[index]['agent_status'] == 4){

			disable = "<a class='btn btn-primary' href='disableUser.php?action=agent&value=enable&&user="+data[index]['id']+"'>Enable</a>";
		}

		else {

			disable = "<a class='btn btn-danger' href='disableUser.php?action=agent&value=disable&&user="+data[index]['id']+"'>Disable</a>";
		}

		ag_table = ag_table+"<tr id='tr"+data[index]['id']+"'><td>"+data[index]['id']+"</td><td>"+data[index]['agent_username']+"</td><td>"+data[index]['agent_code']+"</td><td>"+data[index]['phone_no']+"</td><td><img src='"+data[index]['imagename']+"' width='100'/></td><td>"+disable+"</td><td><a class='btn btn-primary' href='transaction.php?action=agent&&agent-select="+data[index]['id']+"'>Select</a></td></tr>";
			//console.log(data[index]['phone_no']);

		});

			$("#agent_table").html(ag_table+"</table>");
	}

	else{

		var ag_table = agent_table;
		$.each(data , function(index , value){

		ag_table = ag_table+"<tr id='tr"+data[index]['id']+"' onclick = 'select_customer("+data[index]['id']+")'><td>"+data[index]['id']+"</td><td>"+data[index]['agent_username']+"</td><td>"+data[index]['agent_code']+"</td></tr>";

		});

			$("#agent_table").html(ag_table+"</table>");
			//console.log('here');
	}


}

function create_customer_table(data,user){

	//console.log(customer_table);

	var cus_table = customer_table;
	var disable;

	$.each(data , function(index , value){

		if(data[index]['user_status'] == 4){

			disable = "<a class='btn btn-primary' href='disableUser.php?action=customer&value=enable&&user="+data[index]['user_id']+"'>Enable</a>";
		}

		else {

			disable = "<a class='btn btn-danger' href='disableUser.php?action=customer&value=disable&&user="+data[index]['user_id']+"'>Disable</a>";
		}

cus_table = cus_table+"<tr><td>"+data[index]['username']+"</td><td>"+data[index]['phone_no']+"</td><td><img src='"+data[index]['imagename']+"' width='100'/></td><td>"+data[index]['m_id']+"</td><td>"+disable+"</td><td><a href = 'change_password.php?action=customer&&customerid="+data[index]['user_id']+"'>Change Password</a></td><td><a class='btn btn-primary' href='transaction.php?action=customer&&customer-select="+data[index]['user_id']+"'>Select</a></td></tr>";

});

//console.log(data);
$("#customer_table").html(cus_table+"</table>");
highlight_agent(user);

}

function create_super_select(data){

	if(action == 'agent'){

		var disable;

			var ag_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th><th>Phone Number</th><th>Image</th><th>Disable Agent</th><th>Select Agent</th></tr>";

			$.each(data , function(index , value){

				if(data[index]['agent_status'] == 4){

			disable = "<a class='btn btn-primary' href='disableUser.php?action=agent&value=enable&&user="+data[index]['id']+"'>Enable</a>";
		}

		else {

			disable = "<a class='btn btn-danger' href='disableUser.php?action=agent&value=disable&&user="+data[index]['id']+"'>Disable</a>";
		}

		ag_table = ag_table+"<tr id='tr"+data[index]['id']+"'><td>"+data[index]['id']+"</td><td>"+data[index]['agent_username']+"</td><td>"+data[index]['agent_code']+"</td><td>"+data[index]['phone_no']+"</td><td><img src='"+data[index]['imagename']+"' width='100'</td><td>"+disable+"</td><td><a class='btn btn-primary' href='transaction.php?action=agent&&agent-select="+data[index]['id']+"'>Select</a></td></tr>";
			//console.log(data[index]['phone_no']);

		});

			$("#agent_table").html(ag_table+"</table>");
	}
	
	else{

		var aget_tble = lvl2agents_table;

	$.each(data, function(index,value){

		aget_tble = aget_tble+"<tr onclick='select_super("+data[index]['id']+")' id='suptr"+data[index]['id']+"'><td>"+data[index]['id']+"</td><td>"+data[index]['agent_username']+"</td><td>"+data[index]['agent_code']+"</td></tr>";

	});

$("#cus_dta").hide();
$("#select_lvl2").html(aget_tble+"</table>");
$("#select_lvl2").show();
$("#select_lvl2_header").show();

	}

	
//console.log(aget_tble);

}


function highlight_agent(agent){

	get_database('get_username',agent,'username');
	$("#tr"+selected_agent+"").removeClass('success');
	$("#tr"+agent+"").addClass('success');

selected_agent = agent;

}