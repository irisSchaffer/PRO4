$(document).ready(function(){
	// Adding spans to the main navigation items for smooth mouseOver effect
	$("#mainMenu > ul > li > a").prepend("<span class='hoverHelp'></span>");
/*	$("#mainMenu .hasSub ul").mouseover(function(){
		$(this).parent().children("a").children("span.hoverHelp").css("opacity", 1);
	});
	$("#mainMenu .hasSub ul").mouseout(function(){
		$(this).parent().children("a").children("span.hoverHelp").css("opacity", 0);
	});*/

	initSignUp();
	initInviteUser();
	initDepartment();
	initMilestonePlan();
	initTodoList();
	showErrors();

	initEditProjectDetails();
	$("#signUp").click(function(event){
		event.preventDefault();
		$("#signUpDialog").dialog("open");
	});
	$("#inviteMemberButton").click(function(event){
		event.preventDefault();
		$("#inviteMember").dialog("open");
	});
	$("#changeProjectInfos").click(function(event){
		event.preventDefault();
		$("#editProjectDetails").dialog("open");
	});
	// Coloring the Calendars rows
	$(".calendar tr:even td").addClass("evenRow");
	$(".calendar tr:odd td").addClass("oddRow");
	// Coloring the cell according to mouseOver
	$(".calendar td").mouseover(function(){
		var length = $.trim($(this).text()).length;
		if (length > 0){
			$(this).addClass("dayHover");
		}
	});
	$(".calendar td").mouseout(function(){
		var length = $.trim($(this).text()).length;
		if (length > 0){
			$(this).removeClass("dayHover");
		}
	});
	// Adding a new entry to todo-lists


});

function initTodoList(){
/*
	$(".todoContainer .todoFooter").click(function(){
		var todoInput = jQuery("<input/>",{
			"type": "text",
		});
		todoInput.hide();
		todoInput.keypress(function(event){
			if (event.which == 13 ) {
     			event.preventDefault();
   				if($.trim($(this).val()).length > 0){
   					var newEntry = "<li>"+$(this).val()+"</li>";
   					$(this).replaceWith(newEntry);
   				}else{
   					$(this).remove();
   				}
   			}
		});
		todoInput.blur(function(){
			$(this).remove();
		});
		todoInput.slideDown(250);
		$(this).prev().append(todoInput);
		todoInput.focus();
	});
*/
	$("#addTodoList").dialog({
					title: "New To-Do List",	
					autoOpen: false,
					resizable: false,
					draggable: false,
					position: {
						my: "center", 
						at: "center", 
						of: $("#content")
					},
					width: 400,
					minWidth: 400,
					show: {
						effect: "fade",
						duration: 250
					},
					hide: {
						effect: "fade",
						duration: 250
					},
					modal: true,
					buttons: {
						"Add new To-Do List": function(){/*
							event_name = $("#newEvent_name").val();
							event_start_date = $("#newEvent_start_date").val();
							event_description = $("#newEvent_description").val();
							var newEvent = jQuery("<div/>",{
								"text": event_name,
								"title": event_description,
								"class": "event"
							});
							newEvent.on("click",function(){
								editEvent = true;
								changingEvent = $(this);
								event_name = $(this).text();
							});
							$(".calendar td[title='"+event_start_date+"']").append(newEvent);*/
							$(this).submit();
							$(this).dialog("close");
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
}
function openAddTodoList(){
	$("#addTodoList").dialog("open");
}
function initDepartment(){
		$("#addNewDepartment").dialog({
					title: "New Department",	
					autoOpen: false,
					resizable: false,
					draggable: false,
					position: {
						my: "center", 
						at: "center", 
						of: $("#content")
					},
					width: 400,
					minWidth: 400,
					show: {
						effect: "fade",
						duration: 250
					},
					hide: {
						effect: "fade",
						duration: 250
					},
					modal: true,
					buttons: {
						"Add new Department": function(){
							$(this).submit();
							$(this).dialog("close");
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
}
function openAddDepartment(){
	$("#addNewDepartment").dialog("open");
}

function initCalendar(){
	// Adding hidden dialog-form
	$("body").append("<form id='newEvent' title='New Event'><label for='newEvent_name'>Name:</label><input id='newEvent_name' type='text' /><br /><label for='newEvent_start_date'>Start-Date:</label><input id='newEvent_start_date' type='text' /><br /><label for='newEvent_end_date'>End-Date:</label><input id='newEvent_end_date' type='text' /><br /><label for='newEvent_description'>Description:</label><textarea id='newEvent_description'></textarea></form>");
	$("#newEvent").hide();
	
	// Adding a new event to the calendar
	var day;
	var editEvent;
	var event_name;
	var event_start_date;
	var event_description;
	var changingEvent;
	$(".calendar td").click(function(){
		var length = $.trim($(this).text()).length;
		if (length > 0){
			if(!editEvent){
				// New Entry mode
				$("#newEvent #newEvent_name").val("");
				$("#newEvent #newEvent_description").val("");
				day = $(this);
				$("#newEvent #newEvent_start_date").val(day.attr("title"));
				$("#newEvent #newEvent_end_date").val(day.attr("title"));
				$("#newEvent").dialog({
					title: "New Event",
					buttons: {
						"OK": function(){
							event_name = $("#newEvent_name").val();
							event_start_date = $("#newEvent_start_date").val();
							event_description = $("#newEvent_description").val();
							var newEvent = jQuery("<div/>",{
								"text": event_name,
								"title": event_description,
								"class": "event"
							});
							newEvent.on("click",function(){
								editEvent = true;
								changingEvent = $(this);
								event_name = $(this).text();
							});
							$(".calendar td[title='"+event_start_date+"']").append(newEvent);
							$(this).dialog("close");
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
				$("#newEvent").dialog("open");
			}else{
				// Edit mode
				$("#newEvent #newEvent_name").val(event_name);
				$("#newEvent #newEvent_description").val(event_description);
				day = $(this);
				$("#newEvent #newEvent_start_date").val(day.attr("title"));
				$("#newEvent #newEvent_end_date").val(day.attr("title"));
				$("#newEvent").dialog({
					buttons: {
						"OK": function(){
							event_name = $("#newEvent_name").val();
							event_start_date = $("#newEvent_start_date").val();
							event_description = $("#newEvent_description").val();
							var newEvent = jQuery("<div/>",{
								"text": event_name,
								"title": event_description,
								"class": "event"
							});
							changingEvent.text(event_name);
							newEvent.on("click",function(){
								editEvent = true;
							});
							//$(".calendar td[title='"+event_start_date+"']").append(newEvent);
							$(this).dialog("close");
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					},
					title: "Edit '"+event_name+"'"
					});
				$("#newEvent").dialog("open");
			}
		}
		editEvent = false;
	});
	$("#newEvent").dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		position: {
			my: "center", 
			at: "center", 
			of: $("#content")
		},
		width: 400,
		minWidth: 400,
		show: {
        	effect: "fade",
        	duration: 250
      	},
      	hide: {
        	effect: "fade",
        	duration: 250
      	},
		modal: true
	});
}
function initSignUp(){
	//$("body").append("<form id='signUpDialog' title='Change Project'><div class='radioWrap'><div id='registerWrap' class='selectedMode'><label for='radioRegister'>Register</label><input type='radio' name='checkAction' id='radioRegister' value='register' checked/></div><div id='loginWrap'><label for='radioLogin'>Login</label><input type='radio' name='checkAction' id='radioLogin' value='login' /></div></div><div class='inputsWrap'><label for='signUpEmail'>E-Mail</label><input id='signUpEmail' type='email' /><label for='signUpPassword'>Password</label><input id='signUpPassword' type='password' /></div><div id='repeatPasswordWrap'><label for='signUpPasswordRepeat'>Repeat Password</label><input id='signUpPasswordRepeat' type='password' /></div></form>");
	var register = true;
	$("#signUpDialog input:radio[name='checkAction']").click(function() {
		if ($("input:radio:checked[name='checkAction']").val() == "login"){
			if(!($("#loginWrap").hasClass("selectedMode"))){
				$("#registerWrap").removeClass("selectedMode");
				$("#loginWrap").addClass("selectedMode");
			}
			$("#repeatPasswordWrap").slideUp(250);
			register = false;
		}else{
			$("#repeatPasswordWrap").slideDown(250);
			if(!($("#registerWrap").hasClass("selectedMode"))){
				$("#registerWrap").addClass("selectedMode");
				$("#loginWrap").removeClass("selectedMode");
			}
			$("#registerWrap").addClass("selectedMode");
			register = true;
		}
	});
	$("#signUpDialog").hide();
	$("#signUpDialog").dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		position: {
			my: "center", 
			at: "center", 
			of: $("body")
		},
		width: 400,
		minWidth: 400,
		show: {
        	effect: "fade",
        	duration: 250
      	},
      	hide: {
        	effect: "fade",
        	duration: 250
      	},
		modal: true,
		title: "Sign Up",
		buttons: {
			"Continue": function(){
				$(this).submit();
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function initInviteUser(){
	$("#inviteNewUser").dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		position: {
			my: "center", 
			at: "center", 
			of: $("#content")
		},
		width: 400,
		minWidth: 400,
		show: {
        	effect: "fade",
        	duration: 250
      	},
      	hide: {
        	effect: "fade",
        	duration: 250
      	},
		modal: true,
		title: "Invite New User",
		buttons: {
			"Send Invitation": function(){
				$(this).submit();
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function openInviteUser(){
	$("#inviteNewUser").dialog("open");
}
function initEditProjectDetails(){
	$("body").append("<form id='editProjectDetails' title='Change Project Details'><label for='projectName'>Name:</label><input id='projectName' type='text' /><label for='projectDescription'>Description:</label><input id='projectDescription' type='text' /></form>");	
	$("#editProjectDetails").hide();
	$("#editProjectDetails").dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		position: {
			my: "center", 
			at: "center", 
			of: $("#content")
		},
		width: 400,
		minWidth: 400,
		show: {
        	effect: "fade",
        	duration: 250
      	},
      	hide: {
        	effect: "fade",
        	duration: 250
      	},
		modal: true,
		buttons: {
			"Edit Project": function(){
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function initMembers(){
	$("input:checkbox").removeAttr("checked");
    initInviteMember();
}
function initMilestonePlan(){
	updateMilestones();
	
	$(".milestoneFilter").on("click",function(){
		hideMilestones($(this).attr("id"),$(this).hasClass("disabledMilestone"));
	});
}
function hideMilestones(type, disabled){
	if(!disabled){/*
		var effect = function() {
	 		return $("#milestoneCanvas ."+type).hide();
		};*/
		$("#milestoneCanvas ."+type).hide();
		$("#"+type).addClass("disabledMilestone");
		$("#milestoneCanvas ."+type).addClass("disabledMilestone");
		/*$.when(effect()).done(function() {
	    	updateMilestones();
	  	});*/
  	}else{		
  		$("#milestoneCanvas ."+type).show();
		$("#"+type).removeClass("disabledMilestone");
		$("#milestoneCanvas ."+type).removeClass("disabledMilestone");
	   
  	}
  	updateMilestones();
}
function updateMilestones(){
	var counter = 0;
	var canvas = $("#milestoneCanvas");
	var milestonePlanStart = canvas.attr("data-from");
	var milestonePlanEnd = canvas.attr("data-to");
	var milestonePlanWidth = canvas.width();
	var stepSize = milestonePlanWidth/(milestonePlanEnd-milestonePlanStart);
	var padding = 0;
	var entryHeight = $(".milestone:visible").outerHeight();
	var outerEntryWidth = $(".milestone:visible").outerWidth();
	var innerEntryWidth = $(".milestone:visible").width();
	padding = outerEntryWidth - innerEntryWidth;
	$(".milestone:visible").each(function(){
		var from = $(this).attr("data-from");
		var to = $(this).attr("data-to");
		$(this).css("left",from*stepSize);
		$(this).css("top",counter*entryHeight);
		$(this).css("width",(to-from)*stepSize-padding);
		$(this).attr("title","From: "+ from +" To: "+to);
		$(this).on("mouseenter",function(){
			if(!$(this).children().size() > 0){
				$(this).append("<img id=\"delete\" src=\"img/delete.png\" height=16 width=16 alt=\"delete\">");
				$(this).append("<img id=\"edit\" src=\"img/edit.png\" height=16 width=16 alt=\"edit\">");
				$("#delete").on("click",function(){
					deleteMilestone($(this).parent());
					updateMilestones();
				});
				$("#edit").on("click",function(){
					$("#addMilestone").dialog("open");
					updateMilestones();
				});
			}
		});
		$(this).on("mouseleave",function(){
			$(this).children($("img")).remove();
		});
		counter++;
	});
	canvas.css("height",counter*entryHeight);
}
function deleteMilestone(milestone){
	milestone.empty();
	milestone.remove();
}
function addMilestone(){
	$("#addMilestone").dialog("open");
}
function initAddMilestone(){
	$("body").append("<form id='addMilestone' title='Add new milestone'><label for='milestoneName'>Name:</label><input id='milestoneName' type='text' /><label for='milestoneFrom'>From:</label><input id='milestoneFrom' type='text' /><label for='milestoneTo'>To:</label><input id='milestoneTo' type='text' /></form>");	
	$("#addMilestone").hide();
	$("#addMilestone").dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		position: {
			my: "center", 
			at: "center", 
			of: $("#content")
		},
		width: 400,
		minWidth: 400,
		show: {
        	effect: "fade",
        	duration: 250
      	},
      	hide: {
        	effect: "fade",
        	duration: 250
      	},
		modal: true,
		buttons: {
			"Add milestone": function(){
				$("#milestoneCanvas").append("<div data-from=\""+$("#milestoneFrom").val()+"\" data-to=\""+$("#milestoneTo").val()+"\" class=\"milestone one\">"+$("#milestoneName").val()+"</div>");
				updateMilestones();
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function showErrors(){
	$("ul.error").hide().slideDown(250).delay(3000).slideUp(250);
	$(".success").hide().slideDown(250).delay(3000).slideUp(250);
}
function openSignup(){
	$("#signUpDialog").dialog("open");
}