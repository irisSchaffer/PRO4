$(document).ready(function(){
	// Adding spans to the main navigation items for smooth mouseOver effect
	$("#mainMenu > ul > li > a").prepend("<span class='hoverHelp'></span>");
/*	$("#mainMenu .hasSub ul").mouseover(function(){
		$(this).parent().children("a").children("span.hoverHelp").css("opacity", 1);
	});
	$("#mainMenu .hasSub ul").mouseout(function(){
		$(this).parent().children("a").children("span.hoverHelp").css("opacity", 0);
	});*/
	
	initAbout();
	initSignUp();
	initInviteUser();
	initDepartment();
	initCalendar();
	initMilestonePlan();
	initTodoList();
	initFile();
	initUserToDepartment();
	showErrors();
	
	$(".colorSelector").ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('.colorSelector div').css('backgroundColor', '#' + hex);
			$('.colorSelector').val(hex);
		}
	});
	
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
	$(".todoContainer").each(function(){
		var focus = false;
		$(this).on("mouseenter",function(){
			$(this).children("form").children("p").children("input").slideDown(200);
		});
		$(this).on("mouseleave",function(){
			if (!focus){
				$(this).children("form").children("p").children("input").slideUp(200);
			}
		});
		$(this).children("form").children("p").children("input").focusin(function(){
			focus = true;
		});
		$(this).children("form").children("p").children("input").focusout(function(){
			focus = false;
			$(this).parent().parent().trigger("mouseleave");
		});
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
						"OK": function(){
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
								// Overwriting onDialogclose - Do nothing
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
	var localTime = new Date();
	var localYear = localTime.getFullYear();
	var localMonth = localTime.getMonth()+1;
	var localDay = localTime.getDate();
	//alert("Day: "+localDay+" Month: "+localMonth+" Year: "+localYear);
	$(".calendar td[data-year="+localYear+"][data-month="+localMonth+"][data-day="+localDay+"]").addClass("today");
	
	
	$(".calendar td.clickable").click(function(){
		var length = $.trim($(this).attr("data-day")).length;
		if (length > 0){
			if(!editEvent){
				$("#event_date_year").val($(this).attr("data-year"));
				$("#event_date_month").val($(this).attr("data-month"));
				$("#event_date_day").val($(this).attr("data-day"));
				$("#newEvent").dialog({
					title: "New Event",
					buttons: {
						"OK": function(){
							$("#newEvent").on("dialogclose", function( event, ui ) {
								// Overwriting onDialogclose - Do nothing
							});
							$(this).submit();
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
				openNewEvent();
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
	var url = $(location).attr('href');
	if (url.indexOf("/events/") != -1){
		var pieces = url.split("/events/");
		if (!(typeof pieces === "undefined") && pieces != null){
			pieces = pieces[1].split("/");	
			if(pieces[1] == "edit"){
				$("#newEvent").on("dialogclose", function( event, ui ) {
					history.back(1);
				});
				$("#newEvent").dialog("open");

			}
		}
	}
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
	$("#signUpDialog input[type='password']").keypress(function(event){
		if ( event.which == 13 ) {
			$("#signUpDialog").submit();
		}
	});
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
	var url = $(location).attr('href');
	if (url.indexOf("/milestone_plan/") != -1 && (url.indexOf("/milestone_plan/edit") == -1)){
		updateMilestones();
	}
	initAddMilestone();
	
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
	var canvas = $("#milestoneCanvas");
	
	// Year - Month - Day	
	var startDateRaw = canvas.attr("data-from");
	var startDate = startDateRaw.split("-");
	var startDateObj = new Date (parseInt(startDate[1])+"/"+parseInt(startDate[2])+"/"+parseInt(startDate[0]));
	
	// Year - Month - Day
	var endDateRaw = canvas.attr("data-to");
	var endDate = endDateRaw.split("-");
	var endDateObj = new Date (parseInt(endDate[1])+"/"+parseInt(endDate[2])+"/"+parseInt(endDate[0]));
	

	var milestonePlanEnd = Math.floor((endDateObj-startDateObj)/1000/60/60/24);
	var milestonePlanStart = 0;
	
	var counter = 0;
	var milestonePlanWidth = canvas.width();
	var stepSize = milestonePlanWidth/(milestonePlanEnd-milestonePlanStart);
	var padding = 0;
	var entryHeight = $(".milestone:visible").outerHeight();
	var outerEntryWidth = $(".milestone:visible").outerWidth();
	var innerEntryWidth = $(".milestone:visible").width();
	padding = outerEntryWidth - innerEntryWidth;
	$(".milestone:visible").each(function(){
		var milestoneStartRaw = $(this).attr("data-from");
		var milestoneStartDate = milestoneStartRaw.split("-");
		var milestoneStartObj = new Date (parseInt(milestoneStartDate[1])+"/"+parseInt(milestoneStartDate[2])+"/"+parseInt(milestoneStartDate[0]));
		var from = Math.floor((milestoneStartObj-startDateObj)/1000/60/60/24);
		
		var milestoneEndRaw = $(this).attr("data-to");
		var milestoneEndDate = milestoneEndRaw.split("-");
		var milestoneEndObj = new Date (parseInt(milestoneEndDate[1])+"/"+parseInt(milestoneEndDate[2])+"/"+parseInt(milestoneEndDate[0]));
		var to = Math.floor((milestoneEndObj-milestoneStartObj)/1000/60/60/24)+from;
		
		$(this).css("left",from*stepSize);
		$(this).css("top",counter*entryHeight);
		$(this).css("width",(to-from)*stepSize-padding);
		$(this).attr("title","From: "+ $(this).attr("data-from") +"\nTo: "+ $(this).attr("data-to"));
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
			"OK": function(){
				$(this).submit();
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		},
		title: "Milestone"
	});
	var url = $(location).attr('href');
	if (url.indexOf("/milestone/") != -1){
		var pieces = url.split("/milestone/");
		if (!(typeof pieces === "undefined") && pieces != null){
			pieces = pieces[1].split("/");	
			if(pieces[0] == "add"){
				$("#addMilestone").on("dialogclose", function( event, ui ) {
					history.back(1);
				});
				$("#addMilestone").dialog("open");
			}else if(pieces[1] == "edit"){
				$("#addMilestone").on("dialogclose", function( event, ui ) {
					history.back(1);
				});
				$("#addMilestone").dialog("open");
			} 
		}
	}
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
function initUserToDepartment(){
	$("#addUserToDepartment").dialog({
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
		title: "Add User to Department",
		buttons: {
			"Add User": function(){
				$(this).submit();
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
}
function openUserToDepartment(){
	$("#addUserToDepartment").dialog("open");
}
function initAbout(){
	$("#about").dialog({
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
		title: "About",
		buttons: {
			"Close": function(){
				$(this).dialog("close");
			}
		}
	});
}
function openAbout(){
	$("#about").dialog("open");
}
function showErrors(){
	$("ul.error").hide().slideDown(250).delay(3000).slideUp(250);
	$(".success").hide().slideDown(250).delay(3000).slideUp(250);
}
function openSignup(){
	$("#signUpDialog").dialog("open");
}