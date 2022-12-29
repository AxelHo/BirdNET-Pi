/home/axelho/BirdNET-Pi/birdnet/bin/python3 /usr/local/bin/daily_plot.py

sqlite3 birds.db
CREATE INDEX "detections_Com_Name" ON "detections" ("Com_Name")
CREATE INDEX "detections_Date_Time" ON "detections" ("Date" DESC, "Time" DESC)

/usr/local/bin/gotty --address localhost -w -p 8888 -P terminal --title-format "BirdNET-Pi Terminal" login
