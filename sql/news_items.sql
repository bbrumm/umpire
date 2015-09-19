CREATE TABLE news (
        id int(11) NOT NULL AUTO_INCREMENT,
        title varchar(128) NOT NULL,
        slug varchar(128) NOT NULL,
        text text NOT NULL,
        PRIMARY KEY (id),
        KEY slug (slug)
);

INSERT INTO NEWS (id, title, slug, text) VALUES (1, 'Article title', 'slug1', 'This is the body of the article. Blah blah blah. The end');
INSERT INTO NEWS (id, title, slug, text) VALUES (1, 'More news', 'slug2', 'Another article is here. This is another story. You should read it.');
