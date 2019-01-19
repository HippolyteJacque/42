using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SingleTargetSpell : MonoBehaviour {

	[SerializeField] ParticleSystem particle;

	[HideInInspector]public float damage = 10;
	[HideInInspector] public float manaCost = 10f;

	public bool boostedFireball;

	public void addSpell(int c)
	{
		if (c == 1)
		{
			SpellsTab.instance.spell1.RemoveAllListeners();
			SpellsTab.instance.spell1.AddListener(activeSpell);
		}
		if (c == 2)
		{
			SpellsTab.instance.spell2.RemoveAllListeners();
			SpellsTab.instance.spell2.AddListener(activeSpell);	
		}
		if (c == 3)
		{
			SpellsTab.instance.spell3.RemoveAllListeners();
			SpellsTab.instance.spell3.AddListener(activeSpell);
		}
		if (c == 4)
		{
			SpellsTab.instance.spell4.RemoveAllListeners();
			SpellsTab.instance.spell4.AddListener(activeSpell);
		}
	}
	public void activeSpell()
	{
		if (manaCost > MayaMove.instance.Mana)
			return;
		
		MayaMove.instance.Mana -= manaCost;
		Ray ray;
		RaycastHit hit;
		ray = Camera.main.ScreenPointToRay(Input.mousePosition);

		if(Physics.Raycast(ray, out hit, Mathf.Infinity, 1<<9)){
			if (boostedFireball)
				particle.transform.localScale = Vector3.one * 1f;
			else
				particle.transform.localScale = Vector3.one * 0.5f;
			
			particle.transform.position = hit.collider.gameObject.transform.position;
			particle.Play();
			hit.collider.GetComponent<ZombieMove>().TakeDmg(damage);//takeDamage;

		}

	}

}
