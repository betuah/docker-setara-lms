db.createUsers(
    {
        user: "MONGO_DB_USER",
        pwd: "MONGO_DB_PASSWORD",
        roles: [
            {
                role : "userAdmin",
                db : "db"
            }
        ]
    }
)
