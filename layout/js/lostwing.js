//console.log(logged_agent_id);
var selected_agent;
var agent_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th><th>Lost Win</th></tr>";
var customer_table = "<table class='table table-responsive table-bordered table-striped' style = 'width:535px'><tr><th>Username</th><th>Date Time</th><th>Member Id</th><th>Lost Win</th></tr>";
var lvl2agents_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th><th>Lost Win</th></tr>";
 var datastr = [] ;
//var lvl2_lostwin = 0;
 

function alter_agent(id,username,code,lostwin){

//console.log(lostwin);
agent_table = "<table class='table table-responsive table-bordered table-striped'><tr><th>Agent Id</th><th>Username</th><th>Agent Code</th><th>Lost Win</th></tr>";

	agent_table = agent_table+"<tr class='success' id='tr"+id+"' onclick = 'select_customer("+id+")'><td>"+id+"</td><td>"+username+"</td><td>"+code+"</td><td>"+lostwin+"</td></tr>";

}

function back_super(){

	location.reload();
}


function select_super(id){

	//console.log('here');
	$("#select_lvl2").hide();
	$("#select_lvl2_header").hide();
	$("#back_super").show();

get_database('first',id,'user');
get_database('agent',id,'agent');
		select_customer(id);
		
$("#cus_dta").show();

}

function select_customer(user){

	// console.log(user);
get_database('customer',user,'customer');

}

function getdate(){
    
    var start_date = $("#startdate").val();
    
    var start_time = $("#starttime").val()+$("#starttimemin").val();+':00';
    
    var end_date = $("#enddate").val();
    
    var end_time = $("#endtime").val()+$("#endtimemin").val();+':00';

  datastr['start_date'] = start_date;
  datastr['end_date'] = end_date;
  datastr['start_time'] = start_time;
  datastr['end_time'] = end_time;


if(logged_agent_level == 1){

		get_database('super',logged_agent_id,'super');

	}


else{

	//alter_agent(logged_agent_id,logged_agent_username,logged_agent_code);
	get_database('first',logged_agent_id,'user');
	select_customer(logged_agent_id);
		get_database('agent',logged_agent_id,'agent');
		

}


$("#date").css('height','100px');
$("#data").show();
location.href="#data";

}


function get_database(action,user,lvl){
        
	//console.log(datastr);
	 $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "getcuslostwin.php",
                    data: {'tag':action,'user':user,'start_date':datastr['start_date'],'end_date':datastr['end_date'],'start_time':datastr['start_time'],'end_time':datastr['end_time'],},
                    cache: false,
                    success: function(data)
                    {

    	//console.log(data);


                    	if(lvl=='agent'|| lvl=='superagent' ){create_agent_table(data); }

                    	else if(lvl=='customer'){create_customer_table(data,user);}

                    	else if(lvl =='super'){create_super_select(data)}

						else if(lvl == 'user'){alter_agent(data['data']['id'],data['data']['agent_username'],data['data']['agent_code'],data['lostwin']);}
						else if(lvl == 'username'){ $("#customer_table_header").text(data['agent_username']+'\'s Customers'); }

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
//                                            console.log('error')
					},
                    async:true
                });
}


function create_agent_table(data){

	//console.log('agent');
		var ag_table = agent_table;
		$.each(data , function(index , value){

		ag_table = ag_table+"<tr id='tr"+data[index]['data']['id']+"' onclick = 'select_customer("+data[index]['data']['id']+")'><td>"+data[index]['data']['id']+"</td><td>"+data[index]['data']['agent_username']+"</td><td>"+data[index]['data']['agent_code']+"</td><td>"+data[index]['lostwin']+"</td></tr>";

		});

			$("#agent_table").html(ag_table+"</table>");
			//console.log('here');
	}


function create_customer_table(data,user){

	//console.log('customer');
	var cus_table = customer_table;    
	$.each(data , function(index , value){
		
//lvl2_lostwin + lvl2_lostwin+data['lost_win']
cus_table = cus_table+"<tr><td>"+data[index]['username']+"</td><td>"+data[index]['date_time']+"</td><td>"+data[index]['m_id']+"</td><td>"+data[index]['lost_win']+"</td></tr>";

});

//console.log(data);
$("#customer_table").html(cus_table+"</table>");
highlight_agent(user);

}

function create_super_select(data){

		var aget_tble = lvl2agents_table;

	$.each(data, function(index,value){

		aget_tble = aget_tble+"<tr onclick='select_super("+data[index]['data']['id']+")' id='suptr"+data[index]['data']['id']+"'><td>"+data[index]['data']['id']+"</td><td>"+data[index]['data']['agent_username']+"</td><td>"+data[index]['data']['agent_code']+"</td><td>"+data[index]['lostwin']+"</td></tr>";

	});

$("#cus_dta").hide();
$("#select_lvl2").html(aget_tble+"</table>");
$("#select_lvl2").show();
$("#select_lvl2_header").show();

	}

	
//console.log(aget_tble);

function highlight_agent(agent){

 	get_database('get_username',agent,'username');
	$("#tr"+selected_agent+"").removeClass('success');
	$("#tr"+agent+"").addClass('success');

selected_agent = agent;

}




