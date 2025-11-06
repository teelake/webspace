# Newsletter & Blog Features Documentation

## 📧 Newsletter Subscription

### Features
- ✅ Email validation
- ✅ Duplicate entry prevention
- ✅ Spam protection (honeypot + rate limiting)
- ✅ Success message and form reset
- ✅ Email confirmation (optional)
- ✅ Re-subscription support

### Database Table
```sql
newsletter_subscriptions
- id
- email (unique)
- ip_address
- user_agent
- status (active/unsubscribed)
- subscribed_at
- unsubscribed_at
```

### API Endpoint
**URL:** `/api/newsletter.php`  
**Method:** POST  
**Parameters:**
- `email` (required) - Email address
- `website` (honeypot) - Hidden field for spam protection

**Response:**
```json
{
  "success": true,
  "message": "Thank you for subscribing! Please check your email for confirmation."
}
```

### Usage
The newsletter form is integrated on the homepage. Users can subscribe directly from there.

### Spam Protection
1. **Honeypot Field:** Hidden field that bots typically fill
2. **Rate Limiting:** Max 5 attempts per IP per hour, 3 per email per hour
3. **Duplicate Prevention:** Checks if email already exists

---

## 📝 Blog Features

### 1. Comments System

#### Features
- ✅ Comment submission with validation
- ✅ Spam protection (honeypot + rate limiting)
- ✅ Admin approval workflow
- ✅ Nested comments support (parent_id)
- ✅ Website link support
- ✅ Email notifications to admin

#### Database Table
```sql
blog_comments
- id
- post_id
- parent_id (for nested comments)
- name
- email
- website (optional)
- comment
- ip_address
- user_agent
- status (pending/approved/spam/trash)
- created_at
- updated_at
```

#### API Endpoint
**URL:** `/api/blog-comment.php`  
**Method:** POST  
**Parameters:**
- `post_id` (required) - Blog post ID
- `parent_id` (optional) - Parent comment ID for replies
- `name` (required) - Commenter name
- `email` (required) - Commenter email
- `website` (optional) - Commenter website
- `comment` (required) - Comment text (10-2000 characters)
- `website_hidden` (honeypot) - Hidden field

**Response:**
```json
{
  "success": true,
  "message": "Thank you for your comment! It will be reviewed before being published."
}
```

### 2. Likes System

#### Features
- ✅ Like/unlike functionality
- ✅ IP-based tracking (prevents duplicate likes)
- ✅ Real-time like count updates
- ✅ Visual feedback (heart icon)

#### Database Table
```sql
blog_likes
- id
- post_id
- ip_address
- user_agent
- created_at
```

#### API Endpoint
**URL:** `/api/blog-like.php`  
**Method:** POST  
**Parameters:**
- `post_id` (required) - Blog post ID
- `action` (optional) - 'like', 'unlike', or 'toggle' (default)

**Response:**
```json
{
  "success": true,
  "liked": true,
  "likes_count": 42,
  "message": "Post liked!"
}
```

### 3. SEO Enhancements

#### Features
- ✅ Structured Data (JSON-LD) for BlogPosting
- ✅ Meta tags (title, description, keywords)
- ✅ Canonical URLs
- ✅ Open Graph tags for social sharing
- ✅ Twitter Card tags
- ✅ Reading time calculation
- ✅ Schema.org markup

#### Structured Data
Each blog post includes JSON-LD structured data with:
- Headline
- Description
- Image
- Date published/modified
- Author/Publisher information
- Article section (category)

### 4. Social Sharing

#### Platforms Supported
- Facebook
- Twitter
- LinkedIn
- WhatsApp

#### Implementation
Share buttons are displayed on each blog post with proper URL encoding and social media parameters.

---

## 🗄️ Database Setup

### Step 1: Import New Tables
Run the SQL file to create new tables:
```bash
mysql -u username -p database_name < database/newsletter_comments_likes.sql
```

Or import through phpMyAdmin:
1. Select your database
2. Go to Import tab
3. Choose `database/newsletter_comments_likes.sql`
4. Click Go

### Step 2: Verify Tables
Check that these tables exist:
- `newsletter_subscriptions`
- `newsletter_attempts`
- `blog_comments`
- `blog_likes`

### Step 3: Update Existing Posts
The schema adds new columns to `blog_posts`:
- `likes_count` (default: 0)
- `comments_count` (default: 0)
- `meta_keywords`
- `canonical_url`
- `reading_time`

These will be automatically added when you run the migration SQL.

---

## 🔧 Configuration

### Newsletter Settings
No additional configuration needed. The system uses:
- `SITE_EMAIL` from `config/config.php` for notifications
- Email confirmation is sent automatically

### Comment Settings
- Comments require admin approval by default
- Status: `pending` → `approved` (via admin panel)
- Rate limiting: 10 comments per IP per hour

### Like Settings
- One like per IP address per post
- Likes are tracked by IP (no user accounts required)

---

## 📊 Admin Panel Integration

### Newsletter Management
To manage newsletter subscriptions, you'll need to add an admin page:
- View all subscribers
- Export email list
- Unsubscribe users
- View subscription statistics

### Comment Management
Comments can be managed in the admin panel:
- View pending comments
- Approve/reject comments
- Mark as spam
- Delete comments
- Reply to comments

---

## 🚀 Usage Examples

### Newsletter Subscription
```javascript
// Form automatically handles submission
// No additional code needed on frontend
```

### Blog Comments
```javascript
// Comments are submitted via AJAX
// Form is included in blog.php
// Admin approval required before display
```

### Blog Likes
```javascript
// Like button is included in blog.php
// Clicking toggles like/unlike
// Updates count in real-time
```

---

## 🔒 Security Features

### Newsletter
- Honeypot field
- Rate limiting (IP + email)
- Email validation
- Duplicate prevention

### Comments
- Honeypot field
- Rate limiting (10 per IP/hour)
- Input sanitization
- Admin approval workflow
- Spam detection

### Likes
- IP-based tracking
- One like per IP per post
- No user accounts required

---

## 📝 Notes

1. **Comments require approval:** All comments start as `pending` and must be approved in the admin panel before appearing on the site.

2. **Likes are IP-based:** Users can only like a post once per IP address. This prevents abuse but may limit legitimate users behind the same IP.

3. **Newsletter emails:** Confirmation emails are sent using PHP's `mail()` function. For production, consider using PHPMailer with SMTP.

4. **Reading time:** Calculated automatically based on word count (200 words per minute).

5. **SEO:** Structured data helps search engines understand your content better, potentially improving rankings.

---

## 🐛 Troubleshooting

### Newsletter not working
- Check API endpoint is accessible: `/api/newsletter.php`
- Verify database table exists
- Check error logs: `logs/php-errors.log`

### Comments not showing
- Verify comments are approved in admin panel
- Check comment status is `approved`
- Verify database connection

### Likes not updating
- Check API endpoint: `/api/blog-like.php`
- Verify `blog_likes` table exists
- Check browser console for JavaScript errors

---

**Created:** 2024  
**Version:** 1.0  
**Author:** Webspace Innovation Hub Limited

