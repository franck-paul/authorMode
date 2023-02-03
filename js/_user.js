/*global jsToolBar, dotclear */
'use strict';

window.addEventListener('load', () => {
  if (typeof jsToolBar === 'function') {
    dotclear.tbUser = new jsToolBar(document.getElementById('user_desc'));
    dotclear.tbUser.draw('xhtml');
  }
});
