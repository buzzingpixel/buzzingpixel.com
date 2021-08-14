---
title: Category Construct 2.1.0
slug: category-construct-2-1-0
date: 2016-01-14 10:00 AM
---

Category Construct 2.1.0 has just been released and fixes a couple of bugs:

- Fixed a bug where the direct_parent tag parameter would not accept a pipe delimited list of parent IDs
- Fixed a bug where, under certain circumstances, categories might not be ordered correctly
- Fixed a bug where category image directories would not be parsed

This release also adds a new tag parameter:

- `parent_id_with_children="2|4|6"`

This allows you to get a specific parent ID with the entire rest of the child tree.

Also new in 2.1.0 is the ability to access parent variables from the current child scope like so:

- `{construct:parent_l1:cat_name}`
- `{construct:parent_l2:cat_url_title}`
- `{construct:parent_l3:cat_image}`

As always, 2.1.0 is a free update to existing users and if you have already purchased a copy of Category Construct, you can [download it over at devot:ee][Category Construct Devotee]. If you have not purchased Category Construct yet, [head on over and get your copy][Category Construct Devotee]. It’s only $15.00 and will make your life working with category output a whole lot easier!

To learn more about Category Construct, you can [head over to learn more][Category Construct] and [read the documentation][Category Construct Docs].

[Category Construct Devotee]: https://devot-ee.com/add-ons/category-construct
[Category Construct]: /software/category-construct
[Category Construct Docs]: /software/category-construct/documentation
