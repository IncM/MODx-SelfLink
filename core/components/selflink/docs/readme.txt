--------------------
Snippet: SelfLinkPlus
--------------------
Version: 0.2-rc1
Released: June 16, 2011
Since: March 8, 2011
Author: Bert Oost <bertoost85@gmail.com>
Author: Bruno Perner <b.perner@gmx.de>
ReAuthor: IncM <deamonblag@gmail.com>
v0.2 founded from: Anselm Hannemann (www.novolo.de)

To navigate up, next or previous from the current resource or from the given resource id
Usefull if you wish a 'previous' and/or 'next' link on your page, and even possible to
link to the parent page.

Note: This is based on the menuindex on this moment. In the future more options will be added

Example usage:

[[!selfLink? &direction=`next`]]

Options:

direction - [required] The way the link should go; possible values are 'up', 'next' and 'prev'
id - [optional] The resource id where the direction should come from, defaults the current page
linktext - [optional] The text wich should appear instead of the pagetitle or longtitle
tpl - [optional] Your chunkname for the view of the link, by default a simple <a> tag is returned
usecss - [optional] To use custom CSS class