import mysql.connector, json, time, sys

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="xxxyyy",
    database="leberkasrechner"
)

cursor = db.cursor()

json_url_standard = "https://cdn.phipsiart.at/butchers.json"
json_url = input(f"Bitte URL eingeben (leer lassen für Standardwert: {json_url_standard}): ")
if json_url == "":
    json_url = json_url_standard

# JSON-Datei einlesen
with open('static/butchers.json', encoding='utf-8') as f:
    data = json.load(f)

total_entries = len(data)
current_entry = 0

# Für jeden Eintrag in der JSON-Datei
for entry in data:
    current_entry += 1
    progress = (current_entry / total_entries) * 100
    # Überprüfen, ob alle erforderlichen Schlüssel vorhanden sind
    if 'id' in entry and 'lat' in entry and 'lon' in entry and 'tags' in entry:
        # Überprüfen, ob Eintrag mit derselben ID vorhanden ist
        print(f"Processing entry with ID {entry['id']}, Progress: {progress:.2f}%, Action: ", end='')
        cursor.execute("SELECT * FROM butchers WHERE id = %s", (entry['id'],))
        result = cursor.fetchone()
        
        # Wenn Eintrag vorhanden, aktualisieren, ansonsten hinzufügen
        if result:
            cursor.execute("UPDATE butchers SET lat = %s, lon = %s, tags = %s WHERE id = %s",
                           (entry['lat'], entry['lon'], json.dumps(entry['tags']), entry['id']))
            print("update")
        else:
            cursor.execute("INSERT INTO butchers (id, lat, lon, tags) VALUES (%s, %s, %s, %s)",
                           (entry['id'], entry['lat'], entry['lon'], json.dumps(entry['tags'])))
            print("insert")
        print(f"Entry {entry['id']} processed successfully")
    else:
        print(f"Skipping entry with ID {entry['id']}, Progress: {progress:.2f}%, Action: nothing (missing required keys)")

# Änderungen in der Datenbank bestätigen
db.commit()

print("ALL DATA UPDATED SUCCESSFULLY\n===================")
doRemoveDoubles = input("Would you like to remove entries which are not presented in the new data anymore? (y/n): ")
if(doRemoveDoubles == "n"):
    print("Program finished")
elif (doRemoveDoubles == "y"):
    print("REMOVING DELETED ENTRIES FROM DATABASE...")
    time.sleep(3)

    # Gelöschte Einträge löschen
    total_entries = len(data)
    for entry in data:
        id = entry['id']
        percentage = (data.index(entry) + 1) / total_entries * 100
        print(f"Checking entry {id} - {percentage:.2f}% complete")
        # Alle IDs aus der Datenbank abrufen
        cursor.execute("SELECT id FROM butchers")
        db_ids = {row[0] for row in cursor.fetchall()}

        # IDs aus dem JSON mit IDs aus der Datenbank vergleichen und fehlende IDs löschen
        for entry in data:
            db_ids.discard(entry['id'])

        # Löschen der fehlenden Einträge aus der Datenbank
        for missing_id in db_ids:
            print(f"Deleting entry with ID {missing_id}")
            cursor.execute("DELETE FROM butchers WHERE id = %s", (missing_id,))
else:
    print("No valid feedback. Exiting script...")


# Änderungen in der Datenbank bestätigen
db.commit()


db.close()
print("Script executed successfully.")
sys.exit()