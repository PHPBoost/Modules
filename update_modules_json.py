#!/usr/bin/env python3
"""
Générateur de modules.json pour le serveur de téléchargement PHPBoost.
"""

import json
import os
import sys
import configparser


def parse_ini(filepath: str) -> dict:
    """Parse INI file, handling both standard keys and array-like keys (e.g., rewrite_rules[])"""
    result = {}
    try:
        with open(filepath, encoding="utf-8") as f:
            for line in f:
                line = line.strip()
                # Skip empty lines and comments
                if not line or line.startswith(';') or line.startswith('#'):
                    continue
                # Parse key = value
                if '=' in line:
                    key, value = line.split('=', 1)
                    key = key.strip()
                    value = value.strip().strip('"')
                    # Only store the first occurrence of non-array keys
                    # Skip array-like keys (rewrite_rules[], specific_hooks, etc.)
                    if '[]' not in key and key not in result:
                        result[key] = value
        return result
    except Exception:
        return {}


def generate_modules_json(addons_dir: str) -> list:
    entries = []

    for addon_id in os.listdir(addons_dir):
        addon_path = os.path.join(addons_dir, addon_id)
        if not os.path.isdir(addon_path):
            continue

        config_file = os.path.join(addon_path, "config.ini")
        if not os.path.isfile(config_file):
            continue

        config = parse_ini(config_file)
        if not config or config.get("addon_type") != "module":
            continue

        names        = {}
        descriptions = {}
        genres       = {}

        lang_dir = os.path.join(addon_path, "lang")
        if os.path.isdir(lang_dir):
            for locale in os.listdir(lang_dir):
                desc_file = os.path.join(lang_dir, locale, "desc.ini")
                if not os.path.isfile(desc_file):
                    continue
                desc = parse_ini(desc_file)
                if not desc:
                    continue
                names[locale]        = desc.get("name", "")
                descriptions[locale] = desc.get("desc", "")
                genres[locale]       = desc.get("genre", "")

        thumbnail = ""
        thumb_file = os.path.join(addon_path, f"{addon_id}.png")
        if os.path.isfile(thumb_file):
            thumbnail = f"{addon_id}.png"

        entries.append({
            "id":               addon_id,
            "addon_type":       config.get("addon_type",       "module"),
            "compatibility":    config.get("compatibility",    ""),
            "version":          config.get("version",          ""),
            "author":           config.get("author",           ""),
            "author_mail":      config.get("author_mail",      ""),
            "author_website":   config.get("author_website",   ""),
            "creation_date":    config.get("creation_date",    ""),
            "last_update":      config.get("last_update",      ""),
            "fa_icon":          config.get("fa_icon",          ""),
            "hexa_icon":        config.get("hexa_icon",        ""),
            "php_version":      config.get("php_version",      ""),
            "name":             names,
            "description":      descriptions,
            "genre":            genres,
            "thumbnail":        thumbnail,
        })

    entries.sort(key=lambda e: e["id"].lower())
    return entries


def main():
    addons_dir = os.path.dirname(os.path.abspath(__file__))

    if not os.path.isdir(addons_dir):
        print(f"Dossier '{addons_dir}' introuvable.", file=sys.stderr)
        sys.exit(1)

    entries     = generate_modules_json(addons_dir)
    output_file = os.path.join(addons_dir, "modules.json")

    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(entries, f, ensure_ascii=False, indent=4)

    print(f"Généré : {output_file}")
    print(f"{len(entries)} addon(s) indexé(s).")


if __name__ == "__main__":
    main()
