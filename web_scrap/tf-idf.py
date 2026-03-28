import re
import sys
import json
import pickle
import math
import os

if len(sys.argv) != 3:
    print("\nPenggunaan:\n python tfidf_build.py dataset.json output.pickle\n")
    sys.exit(1)

input_file = sys.argv[1]
output_pickle = sys.argv[2]

# Load dataset
with open(input_file, 'r', encoding='utf-8') as f:
    content = json.load(f)

# Load stopword
sw = []
if os.path.exists("stopword.txt"):
    sw = open("stopword.txt").read().split("\n")

def clean_str(text):
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s]', ' ', text)
    return text.strip()

tf_data = {}
df_data = {}

# 1. TF + DF
for i, data in enumerate(content):
    raw_tf = {}
    
    teks = f"{data.get('bab','')} {data.get('pasal','')} {data.get('isi','')}"
    words = clean_str(teks).split()

    for w in words:
        if w in sw or len(w) < 2:
            continue
        raw_tf[w] = raw_tf.get(w, 0) + 1

    total = sum(raw_tf.values())
    tf = {}

    if total > 0:
        for w, c in raw_tf.items():
            tf[w] = c / total

    tf_data[i] = tf

    for w in tf:
        df_data[w] = df_data.get(w, 0) + 1

# 2. IDF
N = len(content)
idf_data = {}
for w in df_data:
    idf_data[w] = 1 + math.log10(N / df_data[w])

# 3. BUILD INDEX + DOC VECTOR
index = {}
doc_vectors = {}
doc_norms = {}

for i in tf_data:
    vector = {}

    for w in tf_data[i]:
        tfidf = tf_data[i][w] * idf_data[w]
        vector[w] = tfidf

        if w not in index:
            index[w] = {}
        index[w][i] = tfidf

    doc_vectors[i] = vector

    # norm untuk cosine similarity
    norm = math.sqrt(sum(v**2 for v in vector.values()))
    doc_norms[i] = norm if norm != 0 else 1

# 4. SIMPAN
with open(output_pickle, 'wb') as f:
    pickle.dump({
        "index": index,
        "documents": content,
        "doc_norms": doc_norms
    }, f)

print(f"✅ Selesai! {len(index)} kata unik diindex.")