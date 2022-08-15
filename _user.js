/*global $, jsToolBar, dotclear */
'use strict';

$(() => {
  if (typeof jsToolBar === 'function') {
    dotclear.tbUser = new jsToolBar(document.getElementById('user_desc'));
    dotclear.tbUser.draw('xhtml');
  }
});
