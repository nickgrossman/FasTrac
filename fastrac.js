

$(document).ready(applyBehaviors);
$(document).ready(setLayout);

var FT = {};
FT.currentTab = { 'section': '', 'value': '' };


function applyBehaviors() {

  $(window).resize(setLayout);
  
  $("#sourcelist a").click(getNewTicketList);
  
  $("#tickets li input").click(function(event) {
		  $(this.parentNode).toggleClass('grouped');
	});
	
	$("#tickets li").hover(
	 function(event) {
	   $(this).addClass('hover');
	 },
	 function(event) {
	   $(this).removeClass('hover');
	 }
	);
  
  $("#tickets li").draggable({
  revert: 'invalid',
  start: function(event, ui) {
      $(this).addClass('beingdragged');
			posTopArray = [];
			posLeftArray = [];
			if ($(this).hasClass("grouped")) {		// Loop through each element and store beginning start and left positions
				$(".grouped").each(function(i) {
					thiscsstop = $(this).css('top');
					if (thiscsstop == 'auto') thiscsstop = 0;	// For IE
					
					thiscssleft = $(this).css('left');
					if (thiscssleft == 'auto') thiscssleft = 0; // For IE

					posTopArray[i] = parseInt(thiscsstop);
					posLeftArray[i] = parseInt(thiscssleft);
				});
			}
			
			begintop = $(this).offset().top;	// Dragged element top position
			beginleft = $(this).offset().left;	// Dragged element left position
		},
		drag: function(event, ui) {
			var topdiff = $(this).offset().top - begintop;		// Current distance dragged element has traveled vertically
			var leftdiff = $(this).offset().left - beginleft;	// Current distance dragged element has traveled horizontally
			
			if ($(this).hasClass("grouped")) {
				$(".grouped").each(function(i) {
					$(this).css('top', posTopArray[i] + topdiff);	// Move element veritically - current css top + distance dragged element has travelled vertically
					$(this).css('left', posLeftArray[i] + leftdiff); // Move element horizontally - current css left + distance dragged element has travelled horizontally
				});
			}
		},
		stop: function (event, ui) {
      $(this).removeClass('beingdragged');
		}
  });

  $("#sourcelist li a:not('.selected')").droppable({
    drop: itemDrop,
    accept: '#tickets li',
    hoverClass: 'target',
    tolerance: 'pointer'
  });
  
  $('#groupby select').change(regroupTicketList);
}

function itemDrop(e, ui) {
  // what happens when an item is dropped on a source list
  
  var droppedEl = ui.draggable.context;
  var dropzoneEl = e.target;
  
  var key = dropzoneEl.getAttribute('ft:key');
  var ticketId = droppedEl.getAttribute('ft:ticket-id');
  var value = dropzoneEl.getAttribute('ft:value');
  
  // tell server
  $.post('./', 
    {
    'action': 'update_ticket', 
    'key': key,
    'ticket_id': ticketId,
    'value': value
    },
    dropResponse); 
    
}

function dropResponse(data, textStatus) {
  if (textStatus == 'success') {
    // hide dragged 
    $('#ticket-' + data).hide(); 
  } else {
    // XXX: send the draggable home! (not sure how to find it atm)
  }
}

function setLayout() {
  $('#sourcelist').height($(window).height() - 20); /* 20 for padding */
  $('#content').height($('#sourcelist').height());
  $('#content').width($(window).width() - 240); /* 240 = width of sourcelist + x-padding on #items */
}

function getNewTicketList(e,ui) {
  var dropzoneEl = e.target;  

  e.preventDefault();
  $('#sourcelist a').removeClass('selected');
  $(dropzoneEl).addClass('selected');
  
  var key = dropzoneEl.getAttribute('ft:key');
  var value = dropzoneEl.getAttribute('ft:value');  
  
  FT.currentTab.section = key;
  FT.currentTab.value = value;
  
  $.get('./',
    {
    'action': 'display_items',
    'key': key,
    'value': value
    },
    loadItems
  );
}

function regroupTicketList(e,ui) {
  
  var key = FT.currentTab.section;
  var value = FT.currentTab.value;  
  var groupby = e.target.value;
  
  $.get('./',
    {
    'action': 'display_items',
    'key': key,
    'value': value,
    'groupby': groupby
    },
    loadItems
  );
}

function loadItems(data, textStatus) {
  $('#tickets')[0].innerHTML = data;
  $("#groupby").show();
  applyBehaviors();
}






// ==========================

function entryDrop(e,ui) {
  var personId = ui.draggable.context.getAttribute('tb:person_id'); 
  var startDate = this.getAttribute('tb:date');
  var entryId = ui.draggable.context.getAttribute('tb:entry_id'); 
  var projectId = this.getAttribute('tb:project_id');
  TP.cell = this;
  TP.dragged = ui.draggable.context;
  
  if (!entryId){
    /* this is a new entry */
    $.post('./', 
      {
      'action': 'create', 
      'person_id': personId,
      'startdate': startDate,
      'project_id': projectId
      },
      entryResponse);
  } else {
    /* this is an update */
    $.post('./', 
      {
      'action': 'update', 
      'entry_id': entryId,
      'startdate': startDate,
      'project_id': projectId
      }, 
      entryResponse);
  }
}

function entryResponse(data, textStatus) {
  var cell = $(TP.cell);
  var cellContent = data;
  var dragged = $(TP.dragged);
  
  /* don't hide items from source list */
  if (!dragged.hasClass('source')) dragged.hide();
  cell.empty();
  cell.append(cellContent);
  
  applyBehaviors();
  refreshLog();
}

function trashDrop(e, ui) {
  ui.draggable.hide();
  $.post('./', {'action': 'delete', 'entry_id': ui.draggable.context.id}, trashResponse);
}

function trashResponse(data, textStatus) {
  refreshLog();
}

function updateOrder(order) {
   $.post('./', 
      {
      'action': 'reorder',
      'new_order': order
      }, 
      orderResponse);
}

function orderResponse(data, textStatus) {
}

function refreshLog(e, ui) {
  $.get('./', {'action': 'list_latest_changes'}, function(data, textStatus){
    $('#log ul').replaceWith(data);
  });
}


function togglePersonHighlight(e) {
  var person_id = e.currentTarget.getAttribute('tb:person_id');
  
  if (!$(e.currentTarget).hasClass('highlighted')) {
    $('.person').addClass('lowlighted');
    $('.person.person-' + person_id).removeClass('lowlighted');
    $(e.currentTarget).addClass('highlighted');
  } else {
    $('.person').removeClass('lowlighted');
    $(e.currentTarget).removeClass('highlighted');
  }
}

function toggleProjectHighlight(e) {
  var project_id = e.currentTarget.parentNode.getAttribute('tb:project_id');
  
  if (!$(e.currentTarget).hasClass('highlighted')) {
    $('.person').addClass('lowlighted');
    $('td').addClass('lowlighted');
    $('td.project-' + project_id).removeClass('lowlighted');
    $('td.project-' + project_id + ' .person').removeClass('lowlighted');
    $(e.currentTarget).addClass('highlighted');
    $(e.currentTarget).removeClass('lowlighted');
  } else {
    $('.person').removeClass('lowlighted');
    $('td').removeClass('lowlighted');
    $(e.currentTarget).removeClass('highlighted');
  }
}

function toggleAdminPanel(e) {
  e.preventDefault();
  $('#admin:hidden').show('fast');
}

/*
#
# Batch Dialog
#
*/
function showBatchDialog(e) {
  var cell = $(e.currentTarget);
  var dialog = cell.find('.batch-dialog');
  
  if (cell.find('.person').length > 0) {
    dialog.fadeIn();
  }
}

function hideBatchDialog(e) {
  e.preventDefault();
  e.stopPropagation();
  var dialog = $(e.currentTarget).parent();
  dialog.fadeOut();
}

  console.log($('#tickets li'));


