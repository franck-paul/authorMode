/*global dotclear */
'use strict';

window.addEventListener('load', () => {
  if (typeof dotclear.ToolBar === 'function') {
    dotclear.tbUser = new dotclear.ToolBar(document.getElementById('user_desc'));
    dotclear.tbUser.draw('xhtml');
  }
});
