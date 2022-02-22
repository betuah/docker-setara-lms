db.createUser([
    {
        user: "MONGO_DB_USER",
        pwd: "MONGO_DB_PASSWORD",
        roles: [
            {
                role : "userAdmin",
                db : "db1"
            }
        ]
    },
    {
        user: "MONGO_DB_USER",
        pwd: "MONGO_DB_PASSWORD",
        roles: [
            {
                role : "userAdmin",
                db : "db2"
            }
        ]
    },
])
