# Blog Content Template for SQL Import

## Format for Providing Blog Content

Please provide your blog posts in the following format. You can provide multiple posts at once.

### Format:
```
POST 1:
Title: [Post Title]
Slug: [url-friendly-slug] (or leave blank to auto-generate)
Category: [Category Name] (must exist in blog_categories table)
Author: [Author Name]
Excerpt: [Short description/excerpt]
Content: [Full blog post content - can be HTML]
Meta Title: [SEO title - optional]
Meta Description: [SEO description - optional]
Meta Keywords: [comma, separated, keywords - optional]
Featured Image: [image filename in uploads/blog/ - optional]
Published Date: [YYYY-MM-DD HH:MM:SS] (or leave blank for current date)
Status: [1 for published, 0 for draft]

---

POST 2:
[Same format as above]
```

## Example:

```
POST 1:
Title: Getting Started with Web Development
Slug: getting-started-with-web-development
Category: Web Development
Author: Webspace Team
Excerpt: Learn the fundamentals of web development and start your journey as a web developer.
Content: <p>Web development is an exciting field...</p><p>Here are the basics...</p>
Meta Title: Getting Started with Web Development - Webspace Innovation Hub
Meta Description: Learn the fundamentals of web development and start building amazing websites.
Meta Keywords: web development, programming, HTML, CSS, JavaScript
Featured Image: web-dev-basics.jpg
Published Date: 2024-01-15 10:00:00
Status: 1
```

## Notes:
- Slug will be auto-generated from title if not provided
- Category must match an existing category in blog_categories table
- Content can include HTML tags
- Featured images should be in the uploads/blog/ directory
- Published date format: YYYY-MM-DD HH:MM:SS
- Status: 1 = published, 0 = draft

