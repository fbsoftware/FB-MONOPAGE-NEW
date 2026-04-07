//=================================
//  Editor Render - ricostruisce canvas
//================================= 
 editor.render = function () {
  $('#canvas').empty();

  editor.state.sections.forEach(section => {
    $('#canvas').append(editor.renderSection(section));
  });

  editor.initSortableWidgets();
  editor.initSortableColumns();
};
