CREATE TABLE read_loans (
    id CHAR(36) NOT NULL,
    status SMALLINT NOT NULL,
    operations INT UNSIGNED NOT NULL,
    amount INT NOT NULL,
    remaining INT NOT NULL,
    currency VARCHAR(6) NOT NULL,
    created DATETIME NOT NULL,
    updated DATETIME,
    PRIMARY KEY (id)
);