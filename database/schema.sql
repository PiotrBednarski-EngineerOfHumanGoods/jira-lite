-- Schemat bazy danych Jira-lite (SQLite)
-- Wygenerowano: 2026-06-21 03:26:49

CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "role" varchar not null default 'developer', "preferences" text);

CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");

CREATE TABLE "projects" ("id" integer primary key autoincrement not null, "name" varchar not null, "description" text, "status" varchar not null default 'active', "deadline" date, "created_by" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("created_by") references "users"("id") on delete cascade);


CREATE TABLE "project_user" ("id" integer primary key autoincrement not null, "project_id" integer not null, "user_id" integer not null, "project_role" varchar not null default 'member', "created_at" datetime, "updated_at" datetime, foreign key("project_id") references "projects"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

CREATE UNIQUE INDEX "project_user_project_id_user_id_unique" on "project_user" ("project_id", "user_id");

CREATE TABLE "tasks" ("id" integer primary key autoincrement not null, "project_id" integer not null, "title" varchar not null, "description" text, "status" varchar not null default 'todo', "priority" varchar not null default 'medium', "assigned_to" integer, "created_by" integer not null, "due_date" date, "created_at" datetime, "updated_at" datetime, foreign key("project_id") references "projects"("id") on delete cascade, foreign key("assigned_to") references "users"("id") on delete set null, foreign key("created_by") references "users"("id") on delete cascade);


CREATE TABLE "tags" ("id" integer primary key autoincrement not null, "name" varchar not null, "color" varchar not null default '#6b7280', "created_at" datetime, "updated_at" datetime);

CREATE UNIQUE INDEX "tags_name_unique" on "tags" ("name");

CREATE TABLE "task_tag" ("task_id" integer not null, "tag_id" integer not null, foreign key("task_id") references "tasks"("id") on delete cascade, foreign key("tag_id") references "tags"("id") on delete cascade, primary key ("task_id", "tag_id"));


CREATE TABLE "attachments" ("id" integer primary key autoincrement not null, "task_id" integer not null, "path" varchar not null, "original_name" varchar not null, "mime_type" varchar, "size" integer not null default '0', "uploaded_by" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("task_id") references "tasks"("id") on delete cascade, foreign key("uploaded_by") references "users"("id") on delete cascade);


CREATE TABLE "audit_logs" ("id" integer primary key autoincrement not null, "user_id" integer, "action" varchar not null, "auditable_type" varchar not null, "auditable_id" integer not null, "changes" text, "created_at" datetime not null default CURRENT_TIMESTAMP, foreign key("user_id") references "users"("id") on delete set null);

CREATE INDEX "audit_logs_auditable_type_auditable_id_index" on "audit_logs" ("auditable_type", "auditable_id");

