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
	initCalendar();
	initMilestonePlan();
	initTodoList();
	initFile();
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
	$(".todoContainer input").slideUp(0);
	$(".todoContainer").on("mouseenter",function(){
		$(this).children("form").children("p").children("input").slideDown(200);
	});
	$(".todoContainer").on("mouseleave",function(){
		$(this).children("form").children("p").children("input").slideUp(200);
	});
	$("#addTodoList").dialog({
					title: "To-Do List",	
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
						"OK": function(){/*
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
							$("#addTodoList").on("dialogclose", function( event, ui ) {
							} );
							$(this).submit();
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});

	var url = $(location).attr('href');
	if (url.indexOf("/to-do/") != -1){
		var pieces = url.split("/to-do/");
		if (!(typeof pieces === "undefined") && pieces != null){
			pieces = pieces[1].split("/");
			if(pieces[1] == "edit"){
				$("#addTodoList").on("dialogclose", function( event, ui ) {
					history.back(1);
				} );
				$("#addTodoList").dialog("open");
			}
		}
	}
}
function openAddTodoList(){
	$("#addTodoList").dialog("open");
}
function initDepartment(){
		$("#addNewDepartment").dialog({
					title: "Edit Department",	
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
						"OK": function(){
							$("#addNewDepartment").on("dialogclose", function( event, ui ) {
								
							});
							$(this).submit();
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});

	var url = $(location).attr('href');
	if (url.indexOf("/departments/") != -1){
		var pieces = url.split("/departments/");
		if (!(typeof pieces === "undefined") && pieces != null){
			pieces = pieces[1].split("/");	
			if(pieces[1] == "edit"){
				$("#addNewDepartment").on("dialogclose", function( event, ui ) {
					history.back(1);
				});
				$("#addNewDepartment").dialog("open");

			}
		}
	}
}
function openAddDepartment(){
	$("#addNewDepartment").dialog("open");
}

function initCalendar(){
	// Adding hidden dialog-form
	$("#newEvent").hide();
	
	// Adding a new event to the calendar
	var editEvent;
	
	$(".event").on("click",function(){
		editEvent = true;
	});
	$(".calendar td").click(function(){
		var length = $.trim($(this).attr("data-day")).length;
		if (length > 0){
			if(!editEvent){
				// New Entry mode
				$("#newEvent").dialog({
					title: "New Event",
					buttons: {
						"OK": function(){
							$(this).submit();
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
				openNewEvent();
			}else{
				
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
		modal: true,
		buttons: {
			"OK": function(){
				$(this).submit();
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		},
		title: "Event"
	});
}
function openNewEvent(){
	$("#newEvent").dialog("open");
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
function initFile(){

	$(".fileContainer h5 img").on("click",function(event){
		var deleteLink = $(this).attr("data-href");
		$(location).attr('href',deleteLink);
		event.preventDefault();
	});
	$("#newFileButton").on("click",function(){
		showNewFile();
	});
	$("#myFile_file").on("change",function(){
		$("#fileURL").val($("#myFile_file").val());
	});
	$("#cheatButton").on("click",function(){
		$("#myFile_file").click();
		return false;
	});
	$("#newFile").dialog({
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
		title: "New File",
		buttons: {
			"Upload File": function(){
				$(this).submit();
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function showNewFile(){
	$("#newFile").dialog("open");
}
function showErrors(){
	$("ul.error").hide().slideDown(250).delay(3000).slideUp(250);
	$(".success").hide().slideDown(250).delay(3000).slideUp(250);
}
function openSignup(){
	$("#signUpDialog").dialog("open");
}