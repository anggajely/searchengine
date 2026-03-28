import sys
import json
import pickle
import re
import math

def clean_str(text):
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s]', ' ', text)
    return text.strip()

try:
    if len(sys.argv) < 4:
        print(json.dumps([]))
        sys.exit(1)

    pickle_path = sys.argv[1]
    rank = int(sys.argv[2])
    keyword = sys.argv[3]
    filter_bab = sys.argv[4] if len(sys.argv) > 4 else "all"

    # Load index
    with open(pickle_path, 'rb') as f:
        data = pickle.load(f)

    index = data["index"]
    documents = data["documents"]
    doc_norms = data["doc_norms"]

    words = clean_str(keyword).split()

    scores = {}
    match_count = {}

    # 1. SCORING
    for w in words:
        if w not in index:
            continue

        for doc_id, val in index[w].items():

            bab = documents[doc_id].get("bab", "")

            if filter_bab != "all" and filter_bab not in bab:
                continue

            scores[doc_id] = scores.get(doc_id, 0) + val
            match_count[doc_id] = match_count.get(doc_id, 0) + 1

    # 2. NORMALISASI + BONUS MATCH
    results = []
    for doc_id in scores:

        score = scores[doc_id]

        # cosine normalization
        score = score / doc_norms.get(doc_id, 1)

        # bonus jika banyak kata cocok
        score += match_count[doc_id] * 0.5

        doc = documents[doc_id].copy()
        doc["score"] = score

        results.append(doc)

    # 3. TITLE BOOST (ringan)
    clean_keyword = clean_str(keyword)
    for doc in results:
        if clean_keyword in clean_str(doc["pasal"]):
            doc["score"] *= 2

    # 4. SORT
    results = sorted(results, key=lambda x: x["score"], reverse=True)

    print(json.dumps(results[:rank]))

except Exception as e:
    print(json.dumps([{
        "bab": "ERROR",
        "pasal": "QUERY",
        "isi": str(e),
        "score": 0
    }]))