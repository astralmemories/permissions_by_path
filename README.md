# Permissions by Path

The **Permissions by Path** custom Drupal module allow administrators to grant editors access to create and edit pages within a selected path of a website.

## Configuration

Let's say your Drupal site has the following user roles:
- Administrator (Machine name: administrator)
- Content Editor (Machine name: content_editor)
- Limited Content Editor (Machine name: limited_content_editor)

And you have two teams that should have limited access to different paths inside the same website. The **Marketing** team should have limited access to publish and edit pages within the **/news** path, and the **Help Desk** team should have limited access to publish and edit pages within the **/helpdesk** path.

**Marketing:**
- marketing-user1
- marketing-user2
- marketing-user3

**Help Desk:**
- help-desk-user1
- help-desk-user2
- help-desk-user3

Go to the **Permissions by Path configuration screen**:
/admin/config/permissions_by_path

Check the **Enable Module** checkbox.

**List of user roles that are not affected by this module:**
*(Write one user role ID per line)*
- administrator
- content_editor

**List of Content Types (Page types) that will be affected by this module:**
*(One node Content Type ID per line)*
- landing_page
- page
- article

**Permissions Path1:** /news

**List of users with access to Path1:**
*(One username per line)*
- marketing-user1
- marketing-user2
- marketing-user3

**Permissions Path2:** /helpdesk

**List of users with access to Path2:**
*(One username per line)*
- help-desk-user1
- help-desk-user2
- help-desk-user3

Click the **Save configuration** button at the end of the configuration page.